<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Home_Controller {

    private  $_count = 10;
    private $_feedDao;
    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
        $this->template->appendJSImport('home.js')
                       ->appendJSImport('feed.js');
        $this->_feedDao = WeLearn_DAO_DAOFactory::create('FeedDAO');

    }

    public function index()
    {
        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
        $feeds_usuario = $this->carregar_feeds('','',$this->_count);

        $this->load->helper('paginacao_cassandra');
        $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);

        $qtdFeeds = count($feeds_usuario);
        $comentarios_feed = array();

        $this->load->helper('comentarios_feed');

        for($i = 0; $i < $qtdFeeds; $i++){
            $comentarios_feed[$i] = carregar_comentarios('','', 3, $feeds_usuario[$i]->getId());
        }

        $partialCriarFeed = $this->template->loadPartial(
            'form',
             array('formAction' => 'feed/criar_feed'),
            'usuario/feed'
        );

        $partialCriarComentario = $this->template->loadPartial(
            'form',
            array('formAction'=>'comentario_feed/criar','formExtra' => array('id'=>"form-comentario-criar",'name'=>'form-comentario-criar', 'style'=> 'display: none'),'usuarioAutenticado' => $usuarioAutenticado),
            'usuario/feed/comentario'
        );
        $partialListarFeed= $this->template->loadPartial(
            'lista',
            array(
                  'qtdFeeds' => $qtdFeeds,
                  'feeds_usuario' => $feeds_usuario,
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

            $qtdFeeds = count($feeds_usuario);
            $comentarios_feed = array();

            $this->load->helper('comentarios_feed');
            for($i = 0; $i < $qtdFeeds; $i++){
                $comentarios_feed[$i] = carregar_comentarios('','', 2, $feeds_usuario[$i]->getId());
            }

            $response = array(
                'success' => true,
                'htmlListaFeeds' => $this->template->loadPartial(
                    'lista',
                    array(
                        'qtdFeeds' => $qtdFeeds,
                        'feeds_usuario' => $feeds_usuario,
                        'comentarios_feed' => $comentarios_feed,
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
            $filtros = array('usuario' => $usuarioAutenticado , 'count' => $count+1);
            $feeds = $this->_feedDao->recuperarTodos($de,$ate,$filtros);
            foreach($feeds as $row)
            {
                if($row->getTipo() == WeLearn_Compartilhamento_TipoFeed::VIDEO)
                {
                    $this->autoembed->parseUrl($row->getConteudo());
                    $row->setConteudo($this->autoembed->getEmbedCode());
                }
            }
        }catch(cassandra_NotFoundException $e)
        {
            $feeds = array();
        }
        return $feeds;
    }

    protected function _renderTemplateHome($view = '', $dados = null)
    {

        $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        try{
            $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');

            $listaRandonicaAmigos = $amizadeUsuarioDao->recuperarAmigosAleatorios(
                $usuarioAutenticado,
                3
            );

        }catch(cassandra_NotFoundException $e){

            $listaRandonicaAmigos = null;

        }

        try{
            $gerenciadorPrincipal = $usuarioDao->criarGerenciadorPrincipal($usuarioAutenticado);
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');

            $listaRandonicaCursosCriados = $cursoDao->recuperarTodosPorCriadorAleatorios(
                $gerenciadorPrincipal,
                3
            );

        }catch(cassandra_NotFoundException $e){

            $listaRandonicaCursosCriados = null;

        }

        try{
            $aluno = $usuarioDao->criarAluno($usuarioAutenticado);
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');

            $listaRandonicaCursosInscritos = $cursoDao->recuperarTodosPorAlunoAleatorios(
                $aluno,
                3
            );

        }catch(cassandra_NotFoundException $e){

            $listaRandonicaCursosInscritos = null;

        }


        $widgets = array();

        if(!is_null($listaRandonicaAmigos)){
            $widgets[] = $this->template->loadPartial(
                'widget_amigos',
                array('legenda' => 'Meus Amigos','link' => 'usuario/amigos/listar/'.$usuarioAutenticado->id,'listaRandonicaAmigos' => $listaRandonicaAmigos),
                'usuario/amigos'
            );
        }

        if(!is_null($listaRandonicaCursosCriados)){
            $widgets[] = $this->template->loadPartial(
                'widget_cursos_criados',
                array('legenda' => 'Cursos criados por mim','link'=>site_url('/curso/meus_cursos_criador'),'listaRandonicaCursosCriados' => $listaRandonicaCursosCriados),
                'usuario/cursos'
            );
        }

        if(!is_null($listaRandonicaCursosInscritos)){
            $widgets[] = $this->template->loadPartial(
                'widget_cursos_aluno',
                array('legenda' => 'Cursos em que participo','link'=>site_url('/curso/meus_cursos_aluno'),'listaRandonicaCursosInscritos' => $listaRandonicaCursosInscritos),
                'usuario/cursos'
            );
        }

        $this->_barraDireitaSetVar(
            'widgetsContexto',
            $widgets
        );

        parent::_renderTemplateHome($view, $dados);
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */