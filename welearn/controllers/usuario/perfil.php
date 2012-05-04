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
        $usuarioAutenticado=$this->autenticacao->getUsuarioAutenticado();
        $usuarioPerfil=$usuarioDao->recuperar($id);
        $saoAmigos=$amizadeUsuarioDao->SaoAmigos($usuarioAutenticado,$usuarioPerfil);

        $dados=array('id' => $usuarioPerfil->getId(), 'nome' => $usuarioPerfil->getNome(),
                     'sobrenome' => $usuarioPerfil->getSobrenome(), 'email' => $usuarioPerfil->getEmail(),
                     'saoAmigos' => $saoAmigos
                    );
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