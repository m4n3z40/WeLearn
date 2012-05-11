<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends Perfil_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('convite.js');
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
}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */