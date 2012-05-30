
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends Perfil_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    private $_count = 30;

    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('perfil.js')
            ->appendJSImport('feed.js');
    }

    public function index($id)
    {

        $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
        try{
            $usuarioPerfil=$usuarioDao->recuperar($id);
        }catch(cassandra_NotFoundException $e){
            show_404();
        }


        $feeds_usuario = $this->carregarFeeds('','',$usuarioPerfil,$this->_count);
        $this->load->helper('paginacao_cassandra');
        $dadosPaginados = create_paginacao_cassandra($feeds_usuario,$this->_count);

        $partialListarFeed= $this->template->loadPartial(
            'lista',
            array('feeds_usuario' => $feeds_usuario,
                'usuarioAutenticado' => $usuarioAutenticado,
                'usuarioPerfil' => $usuarioPerfil,
                'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                'haFeeds' => !empty($feeds_usuario),
                'haMaisPaginas' => $dadosPaginados['proxima_pagina'],
                'linkPaginacao' => 'usuario/perfil/proxima_pagina/'.$usuarioPerfil->getId()
            ),
            'usuario/feed'
        );

        $partialCriarFeed = $this->template->loadPartial(
            'form',
            array('formAction' => 'feed/criar_timeline/'.$usuarioPerfil->getId()),
            'usuario/feed'
        );

        $dados=array('usuarioPerfil' => $usuarioPerfil,'usuarioAutenticado' => $usuarioAutenticado, 'criarFeed' => $partialCriarFeed, 'listarFeed' => $partialListarFeed);


        if($usuarioPerfil->getId() != $usuarioAutenticado->getId() )
        {
            $saoAmigos=$amizadeUsuarioDao->SaoAmigos($usuarioAutenticado,$usuarioPerfil);
            $dados['saoAmigos']=$saoAmigos;



            if($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA )// se houver requisicoes de amizade em espera, carrega a partial convites
            {
                $convitePendente = $conviteCadastradoDao->recuperarPendentes($usuarioAutenticado,$usuarioPerfil);
                $dados['convitePendente']=$convitePendente;
            }


        }
        $this->_renderTemplatePerfil('usuario/feed/index',$dados);
    }


    public function proxima_pagina($usuarioPerfil,$inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }
        try{

            $usuarioPerfil = WeLearn_DAO_DAOFactory::create('UsuarioDAO')->recuperar($usuarioPerfil);
            $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
            $feeds_usuario = $this->carregarFeeds($inicio,'',$usuarioPerfil,$this->_count);
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

    private function carregarFeeds($de='',$ate='',$usuarioPerfil,$count)
    {
        $this->load->library('autoembed');
        try{

            $feedDao = WeLearn_DAO_DAOFactory::create('FeedDAO');
            $filtros = array('usuario' => $usuarioPerfil , 'count' => $count+1);
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

    public function dados_pessoais($idusuarioPerfil)
    {
        $usuarioDAO = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $usuarioPerfil = $usuarioDAO->recuperar($idusuarioPerfil);
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
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

            $saoAmigos=$amizadeUsuarioDao->SaoAmigos($usuarioAutenticado,$usuarioPerfil);
            $dados['saoAmigos']=$saoAmigos;
            if($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA )// se houver requisicoes de amizade em espera, carrega a partial convites
            {
                $convitePendente = $conviteCadastradoDao->recuperarPendentes($usuarioAutenticado,$usuarioPerfil);
                $dados['convitePendente']=$convitePendente;
            }
            $dados['view']=$this->template->loadPartial('dados_pessoais',array('usuarioPerfil' => $usuarioPerfil,'dadosPessoais' => $dadosPessoais, 'pais' => $pais['descricao'], 'estado' => $estado['descricao']),'usuario/perfil');

        $this->_renderTemplatePerfil('usuario/perfil/index',$dados);
    }

    public function dados_profissionais($idusuarioPerfil)
    {
        $usuarioDAO = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $usuarioPerfil = $usuarioDAO->recuperar($idusuarioPerfil);
        $usuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
        $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
        try {
            $dadosProfissionaisDAO = WeLearn_DAO_DAOFactory::create('DadosProfissionaisUsuarioDAO');

            $dadosProfissionais = $dadosProfissionaisDAO->recuperar( $usuarioPerfil->getId() );
        } catch(cassandra_NotFoundException $e) {
            $dadosProfissionais = null;
        }

        $possuiDadosProfissionais = ( $usuarioPerfil->getDadosProfissionais() instanceof WeLearn_Usuarios_DadosProfissionaisUsuario );

        $dados=array('usuarioPerfil' => $usuarioPerfil, 'usuarioAutenticado' => $usuarioAutenticado);

        $saoAmigos=$amizadeUsuarioDao->SaoAmigos($usuarioAutenticado,$usuarioPerfil);
        $dados['saoAmigos']=$saoAmigos;
        if($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA )// se houver requisicoes de amizade em espera, carrega a partial convites
        {
            $convitePendente = $conviteCadastradoDao->recuperarPendentes($usuarioAutenticado,$usuarioPerfil);
            $dados['convitePendente']=$convitePendente;
        }
        $dados['view']=$this->template->loadPartial('dados_profissionais',array('usuarioPerfil' => $usuarioPerfil, 'dadosProfissionais' => $dadosProfissionais),'usuario/perfil');

        $this->_renderTemplatePerfil('usuario/perfil/index',$dados);
    }


}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */


