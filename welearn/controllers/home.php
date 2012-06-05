<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Home_Controller {

    private  $_count = 10;
    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
        $this->template->appendJSImport('home.js')
        ->appendJSImport('feed.js');
    }

    public function index()
    {
        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
        $comentarios_feed = array();
        $feeds_usuario = $this->carregar_feeds('','',$this->_count);
        $this->load->helper('paginacao_cassandra');
        $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);

        foreach($feeds_usuario as $row){
            $comentarios_feed[$row->getId()] = $this->carregar_comentarios('','',10,$row->getId());
            echo count($comentarios_feed[$row->getId()]);
        }




        $partialCriarFeed = $this->template->loadPartial(
            'form',
             array('formAction' => 'feed/criar_feed'),
            'usuario/feed'
        );

        $partialCriarComentario = $this->template->loadPartial(
            'form',
            array('usuarioAutenticado' => $usuarioAutenticado),
            'usuario/feed/comentario'
        );
        $partialListarFeed= $this->template->loadPartial(
            'lista',
            array('feeds_usuario' => $feeds_usuario,
                  'comentarios_feed' => $comentarios_feed,
                  'usuarioAutenticado' => $usuarioAutenticado,
                  'criarComentario' => $partialCriarComentario,
                  'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                  'haFeeds' => !empty($feeds_usuario),
                  'haMaisPaginas' => $dadosPaginados['proxima_pagina'],
                  'linkPaginacao' => '/home/proxima_pagina'
            ),
            'usuario/feed'
        );
        $dados= array('criarFeed' => $partialCriarFeed , 'listarFeed' => $partialListarFeed,'paginacao' =>$dadosPaginados);
        $this->_renderTemplateHome('usuario/feed/index',$dados);

    }

    public function proxima_pagina($inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }
        try{
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        $feeds_usuario = $this->carregar_feeds($inicio,'',$this->_count);
        $this->load->helper('paginacao_cassandra');
        $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);

        $response = array(
            'success' => true,
            'htmlListaFeeds' => $this->template->loadPartial(
                'lista',
                array(
                    'feeds_usuario' => $feeds_usuario,
                    'usuarioAutenticado' => $usuarioAutenticado,
                    'paginacao' => $dadosPaginados
                ),
                'usuario/feed'
            ),
            'paginacao' => $dadosPaginados
        );

        $json = Zend_Json::encode($response);
        }catch (UUIDException $e) {

            log_message(
                'error',
                'Ocorreu um erro ao tentar recupera uma nova página de feeds '
                    . create_exception_description($e)
            );

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos verificando.
Tente novamente mais tarde.'
            );

            $json = create_json_feedback(false, $error);

        }
        echo $json;

    }

    private function carregar_feeds($de='',$ate='',$count)
    {
        $this->load->library('autoembed');
        try{
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        $feedDao = WeLearn_DAO_DAOFactory::create('FeedDAO');
        $filtros = array('usuario' => $usuarioAutenticado , 'count' => $count+1);
        $feeds = $feedDao->recuperarTodos($de,$ate,$filtros);
        foreach($feeds as $row)
        {
            if($row->getTipo() == WeLearn_Compartilhamento_TipoFeed::VIDEO)
            {
                $isValid=$this->autoembed->parseUrl($row->getConteudo());
                $row->setConteudo($this->autoembed->getEmbedCode());
            }
        }
        }catch(cassandra_NotFoundException $e)
        {
            $feeds = array();
        }
        return $feeds;
    }

    private function carregar_comentarios($de='',$ate='',$count,$idFeed)
    {
        try{
            $filtros = array('idFeed' => $idFeed , 'count' => $count);
            $comentarioFeedDao = WeLearn_DAO_DAOFactory::create('ComentarioFeedDAO');
            $comentarios = $comentarioFeedDao->recuperarTodos($de,$ate,$filtros);
        }catch(cassandra_NotFoundException $e)
        {
            $comentarios = array();
        }
        return $comentarios;
    }


}

/* End of file home.php */
/* Location: ./application/controllers/home.php */