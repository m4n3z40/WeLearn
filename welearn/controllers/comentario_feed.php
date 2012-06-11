<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 03/06/12
 * Time: 16:03
 * To change this template use File | Settings | File Templates.
 */
class Comentario_feed extends Home_Controller
{
    private $_count = 30;

    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {

    }

    public function criar()
    {
        set_json_header();
        $this->load->helper('notificacao_js');
        $this->load->library('form_validation');


        if ($this->form_validation->run() === FALSE) {

            $json = create_json_feedback(false, validation_errors_json());

            exit($json);
        }

        try{
            $feedDAO = WeLearn_DAO_DAOFactory::create('FeedDAO');
            $feed = $feedDAO->recuperar($this->input->post('id-feed-comentario'));
            $comentarioFeedDAO = WeLearn_DAO_DAOFactory::create('ComentarioFeedDAO');
            $comentario = $comentarioFeedDAO->criarNovo();
            $comentario->setCriador($this->autenticacao->getUsuarioAutenticado());
            $comentario->setCompartilhamento($feed);
            $comentario->setDataEnvio(time());
            $comentario->setConteudo($this->input->post('txtComentario'));
            $comentarioFeedDAO->salvar($comentario);

            $htmlComentario = $this->template->loadPartial('lista_comentarios',
                array('comentarios' => array($comentario)),
                'usuario/feed/comentario');
            $response = Zend_Json::encode(array(
                'idfeed'=> $comentario->getCompartilhamento()->getId(),
                'htmlComentario' => $htmlComentario,
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'Comentario foi Salvo com Sucesso!'
                )
            ));
            $json = create_json_feedback(true,'',$response);
        }catch(cassandra_NotFoundException $e){
           $error = create_json_feedback_error_json("Erro, o compartilhamento selecionado não foi encontrado!");
           $json = create_json_feedback(false,$error);
        }
        echo $json;
    }

    public function remover($idComentario){
        try{
            $comentarioDao = WeLearn_DAO_DAOFactory::create('ComentarioFeedDAO');
            $comentarioDao->remover($idComentario);
            $this->load->helper('notificacao_js');
            $response = Zend_Json::encode(array('notificacao' => create_notificacao_array('sucesso','Comentario removido com Sucesso')));
            $json = create_json_feedback(true,'',$response);
        }catch(cassandra_NotFoundException $e){
            $error = create_json_feedback_error_json("Erro, o comentario selecionado não foi encontrado!");
            $json = create_json_feedback(false,$error);
        }
        echo $json;
    }

    public function proxima_pagina($idProximaPagina,$idFeed)
    {


        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }


            $this->load->helper('paginacao_cassandra');
            $this->load->helper('comentarios_feed');
            $comentarios = carregar_comentarios($idProximaPagina,'', 3, $idFeed);
            $response = array(
                'success' => true,
                'htmlListaComentarios' => $comentarios['HTMLcomentarios'],
                'paginacao' => $comentarios['paginacao']
            );

            $json = Zend_Json::encode($response);
            echo $json;

    }

}