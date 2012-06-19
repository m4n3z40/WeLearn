
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends Perfil_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    private $_count = 30;
    private $_usuarioDao;

    function __construct()
    {
        parent::__construct();
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->template->appendJSImport('perfil.js')
            ->appendJSImport('feed.js')
            ->appendJSImport('amizade.js');
    }

    public function index($idUsuario='')
    {
        if( $idUsuario=='' ) {
            show_404();
        }

        try{
            $usuarioPerfil=$this->_usuarioDao->recuperar($idUsuario);
        }catch(cassandra_NotFoundException $e){
            show_404();
        }


        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();

        $feeds_usuario = $this->carregarTimeLine('','',$usuarioPerfil,$this->_count);
        $this->load->helper('paginacao_cassandra');
        $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);

        $qtdFeeds = count($feeds_usuario);
        $comentarios_feed = array();

        $this->load->helper('comentarios_feed');

        for($i = 0; $i < $qtdFeeds; $i++){
            $comentarios_feed[$i] = carregar_comentarios('','', 3, $feeds_usuario[$i]->getId());
        }

        if($usuarioAutenticado->getId() == $usuarioPerfil->getId()){
            $action = 'feed/criar_feed';
        }else{
            $action = 'feed/criar_timeline/'.$usuarioPerfil->getId();
        }

        $partialCriarFeed = $this->template->loadPartial(
            'form',
            array('formAction' => $action),
            'usuario/feed'
        );


        $partialCriarComentario = $this->template->loadPartial(
            'form',
            array('formAction'=>'comentario_feed/criar','formExtra' => array('id'=>"form-comentario-criar",'name'=>'form-comentario-criar', 'style'=> 'display: none'),'usuarioAutenticado' => $usuarioAutenticado),
            'usuario/feed/comentario'
        );

        $partialListarTimeline= $this->template->loadPartial(
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
                'linkPaginacao' => '/home/proxima_pagina',
                'usuarioPerfil' => $usuarioPerfil
            ),
            'usuario/feed'
        );

        $dados=array('usuarioPerfil' => $usuarioPerfil,'usuarioAutenticado' => $usuarioAutenticado, 'criarFeed' => $partialCriarFeed, 'listarTimeline' => $partialListarTimeline);



        $this->_renderTemplatePerfil('usuario/feed/exibir_timeline',$dados);
    }


    public function proxima_pagina($idUsuario,$inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }
        try{

            $usuarioPerfil = $this->_usuarioDao->recuperar($idUsuario);
            $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
            $feeds_usuario = $this->carregarTimeLine($inicio,'',$usuarioPerfil,$this->_count);
            $this->load->helper('paginacao_cassandra');
            $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);

            $response = array(
                'success' => true,
                'htmlListaFeeds' => $this->template->loadPartial(
                    'lista_timeline',
                    array(
                        'feeds_usuario' => $feeds_usuario,
                        'usuarioAutenticado' => $usuarioAutenticado,
                        'usuarioPerfil' => $usuarioPerfil,
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

    private function carregarTimeLine($de='',$ate='',$idUsuario,$count)
    {
        $this->load->library('autoembed');
        try{

            $feedDao = WeLearn_DAO_DAOFactory::create('FeedDAO');
            $filtros = array('usuario' => $idUsuario , 'count' => $count+1);
            $feeds = $feedDao->recuperarTodosTimeline($de,$ate,$filtros);
            foreach($feeds as $row)
            {
                if($row->getTipo()== WeLearn_Compartilhamento_TipoFeed::VIDEO)
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

    public function listar_amigos($idUsuario='')
    {
        if( $idUsuario=='' ) {
            show_404();
        }

        try{
            $usuarioPerfil =$this->_usuarioDao->recuperar($idUsuario);
        }catch(cassandra_NotFoundException $e){
            show_404();
        }
        $amigosDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $filtros= array('count' => $this->_count+1 , 'opcao' => 'amigos' , 'usuario' => $usuarioPerfil);
        try{
            $totalAmigos = $amigosDao->recuperarQtdTotalAmigos($usuarioPerfil);
            $listaAmigos= $amigosDao->recuperarTodos('','',$filtros);
            $this->load->helper('paginacao_cassandra');
            $dadosPaginados=create_paginacao_cassandra($listaAmigos,$this->_count);
            $partialListaAmigos=$this->template->loadPartial('lista',
                array('listaAmigos' => $listaAmigos,
                    'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                    'haAmigos' => $dadosPaginados['proxima_pagina'],
                    'idUsuario' => $usuarioPerfil->getId()
                ),
                'usuario/amigos'
            );
            $dadosView= array('success' => true,'partialListaAmigos' => $partialListaAmigos, 'totalAmigos' => $totalAmigos,'idUsuario' => $usuarioPerfil->getId());
        }catch(cassandra_NotFoundException $e){
            $dadosView= array('success' => false, 'totalAmigos' => 0,'idUsuario'=>$usuarioPerfil->getId());
        }

        $dadosView['usuarioPerfil']= $usuarioPerfil;
        $dadosView['usuarioAutenticado']= $this->autenticacao->getUsuarioAutenticado();
        $this->_renderTemplatePerfil('usuario/amigos/index',$dadosView);


    }

    public function dados_pessoais($idUsuario='')
    {
        if( $idUsuario=='' ) {
            show_404();
        }

        try{
            $usuarioPerfil = $this->_usuarioDao->recuperar($idUsuario);
        }catch(cassandra_NotFoundException $e){
            show_404();
        }
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        try{
            $dadosPessoaisDao = WeLearn_DAO_DAOFactory::create('DadosPessoaisUsuarioDAO');
            $dadosPessoais = $dadosPessoaisDao->recuperar( $usuarioPerfil->getId() );
            $paisEstadoDao = WeLearn_DAO_DAOFactory::create('PaisEstadoDAO', null, false);
            try{
                $codigoPais = $dadosPessoais->getPais();
                $pais = $paisEstadoDao->recuperarPais($codigoPais);
            }catch(cassandra_NotFoundException $e)
            {
                $pais=array('descricao' => '');
            }
            try{
                $codigoEstado = $dadosPessoais->getEstado();
                $estado = $paisEstadoDao->recuperarEstado($codigoEstado);
            }catch(cassandra_NotFoundException $e)
            {
                $estado = array('descricao' => '');
            }
        }catch(cassandra_NotFoundException $e){
            $dadosPessoais = null;
            $pais=array('descricao' => '');
            $estado = array('descricao' => '');
        }

        $dados=array('usuarioPerfil' => $usuarioPerfil, 'usuarioAutenticado' => $usuarioAutenticado);


        $dados['view']=$this->template->loadPartial('dados_pessoais',array('usuarioPerfil' => $usuarioPerfil,'dadosPessoais' => $dadosPessoais, 'pais' => $pais['descricao'], 'estado' => $estado['descricao']),'usuario/perfil');

        $this->_renderTemplatePerfil('usuario/perfil/index',$dados);

    }

    public function dados_profissionais($idUsuario='')
    {
        if( $idUsuario=='' ) {
            show_404();
        }

        try{
            $usuarioPerfil = $this->_usuarioDao->recuperar($idUsuario);
        }catch(cassandra_NotFoundException $e){
            show_404();
        }
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        try {
            $dadosProfissionaisDAO = WeLearn_DAO_DAOFactory::create('DadosProfissionaisUsuarioDAO');

            $dadosProfissionais = $dadosProfissionaisDAO->recuperar( $usuarioPerfil->getId() );
        } catch(cassandra_NotFoundException $e) {
            $dadosProfissionais = null;
        }

        $possuiDadosProfissionais = ( $usuarioPerfil->getDadosProfissionais() instanceof WeLearn_Usuarios_DadosProfissionaisUsuario );

        $dados=array('usuarioPerfil' => $usuarioPerfil, 'usuarioAutenticado' => $usuarioAutenticado);


        $dados['view']=$this->template->loadPartial('dados_profissionais',array('usuarioPerfil' => $usuarioPerfil, 'dadosProfissionais' => $dadosProfissionais),'usuario/perfil');

        $this->_renderTemplatePerfil('usuario/perfil/index',$dados);

    }


    public function meus_cursos_criador($idUsuario='')
    {
        if( $idUsuario=='' ) {
            show_404();
        }

        try{
            $usuarioPerfil = $this->_usuarioDao->recuperar($idUsuario);
        }catch(cassandra_NotFoundException $e){
            show_404();
        }
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
            $criador = $this->_usuarioDao->criarGerenciadorPrincipal(
                $usuarioPerfil
            );

            try {
                $listaCursos = $cursoDao->recuperarTodosPorCriador($criador, '', '', 1000000);
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haCursos' =>  !empty($listaCursos),
                'totalCursos' => count( $listaCursos ),
                'listaCursos' => $listaCursos,
                'usuarioPerfil' => $usuarioPerfil,
                'usuarioAutenticado' => $usuarioAutenticado
            );


            $this->_renderTemplatePerfil('usuario/cursos/meus_cursos_criador', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de cursos que o usuário criou: '
                . create_exception_description($e));
            show_404();
        }

    }


    public function meus_cursos_aluno($idUsuario='')
    {
        if( $idUsuario=='' ) {
            show_404();
        }
        try{
            $usuarioPerfil = $this->_usuarioDao->recuperar($idUsuario);
        }catch(cassandra_NotFoundException $e){
            show_404();
        }
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
            $aluno = $this->_usuarioDao->criarAluno(
                $usuarioPerfil
            );

            try {
                $listaCursos = $cursoDao->recuperarTodosPorAluno($aluno, '', '', 1000000);
            } catch (cassandra_NotFoundException $e) {
                $listaCursos = array();
            }

            $dadosView = array(
                'haCursos' =>  !empty($listaCursos),
                'totalCursos' => count( $listaCursos ),
                'listaCursos' => $listaCursos,
                'usuarioPerfil' => $usuarioPerfil,
                'usuarioAutenticado' => $usuarioAutenticado
            );
            $this->_renderTemplatePerfil('usuario/cursos/meus_cursos_aluno', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao exibir lista de cursos em que o usuário é aluno: '
                . create_exception_description($e));
            show_404();
        }

    }

}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */


