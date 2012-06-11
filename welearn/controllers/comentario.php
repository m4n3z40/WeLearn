<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 03/06/12
 * Time: 16:03
 * To change this template use File | Settings | File Templates.
 */
class Comentario extends Home_Controller
{

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
            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'Comentario foi Salvo com Sucesso!'
                )
            ));
            $json = create_json_feedback(true,'',$response);
        }catch(Exception $e){
           $response = Zend_Json::encode(array(
               'notificacao' => create_notificacao_array(
                   'erro',
                   'Falha ao enviar Comentario!'
               )
           ));
           $json = create_json_feedback(false,'',$response);
        }
        echo $json;
    }

}