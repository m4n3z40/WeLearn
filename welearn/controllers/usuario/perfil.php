<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('perfil')
            ->appendJSImport('convite.js');
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

    private function _renderTemplatePerfil($view = '', $dados=array() )
    {
        $dadosBarraEsquerda = array(
            'usuario' => $dados
        );

        $dadosBarraDireita = array(
            'imagem'=>'implementar imagem',
            'amigos'=>'implementar amigos',
            'cursos'=>'implementar cursos'
        );

        $this->template->setDefaultPartialVar('perfil/barra_lateral_esquerda', $dadosBarraEsquerda)
            ->setDefaultPartialVar('perfil/barra_lateral_direita', $dadosBarraDireita)
            ->render($view, $dados);
    }

}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */