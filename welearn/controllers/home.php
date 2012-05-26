<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Home_Controller {

    private  $_count = 30;
    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

<<<<<<< HEAD
        $this->template->appendJSImport('home.js');
=======
        $this->template->appendJSImport('home.js')
        ->appendJSImport('feed.js');
>>>>>>> 67dcbb78730c568ac5592f65f9630e02a90a648b
    }

    public function index()
    {
        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
        $feeds_usuario = $this->carregarFeeds('','',$this->_count);
        $this->load->helper('paginacao_cassandra');
        $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);
        $partialCriarFeed = $this->template->loadPartial(
            'form',
             array('formAction' => 'feed/criarFeed'),
            'usuario/feed'
        );
        $partialListarFeed= $this->template->loadPartial(
            'lista',
            array('feeds_usuario' => $feeds_usuario,
                  'usuarioAutenticado' => $usuarioAutenticado,
                  'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                  'haFeeds' => !empty($feeds_usuario),
                  'haMaisPaginas' => $dadosPaginados['proxima_pagina']
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
        $feeds_usuario = $this->carregarFeeds($inicio,'',$this->_count);
        $this->load->helper('paginacao_cassandra');
        $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);

        $response = array(
            'success' => true,
            'htmlListaFeeds' => $this->template->loadPartial(
                'lista',
                array(
                    'feeds_usuario' => $feeds_usuario,
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

    private function carregarFeeds($de='',$ate='',$count)
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
        return $feeds;
        }catch(cassandra_NotFoundException $e)
        {
            return array();
        }
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */