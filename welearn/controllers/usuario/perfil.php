<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends Perfil_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    private  $_count = 30;

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
        $usuarioPerfil=$usuarioDao->recuperar($id);

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
                'haMaisPaginas' => $dadosPaginados['proxima_pagina']
            ),
            'usuario/feed'
        );

        $partialCriarFeed = $this->template->loadPartial(
            'form',
            array('formAction' => 'feed/criarTimeLine/'.$usuarioPerfil->getId()),
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
}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */