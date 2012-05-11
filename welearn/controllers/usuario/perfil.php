<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends Perfil_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('perfil.js');
    }

    public function index($id)
    {
        $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
        $usuarioPerfil=$usuarioDao->recuperar($id);
        $saoAmigos=$amizadeUsuarioDao->SaoAmigos($usuarioAutenticado,$usuarioPerfil);

        $dados=array('usuarioPerfil' => $usuarioPerfil,'usuarioAutenticado' => $usuarioAutenticado,
            'saoAmigos' => $saoAmigos
        );

        if($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA )// se houver requisicoes de amizade em espera, carrega a partial convites
        {
                $convitePendente = $conviteCadastradoDao->recuperarPendentes($usuarioAutenticado,$usuarioPerfil);
                $partialExibirConvite = $this->template->loadPartial(
                    'exibicao_convite',
                    array( 'convite_pendente' => $convitePendente,'usuarioAutenticado' => $usuarioAutenticado),
                    'usuario/convite'
                );
                $dados['partialConvitePendente']=$partialExibirConvite;
        }

        $this->_renderTemplatePerfil('usuario/perfil/index',$dados);
    }
}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */