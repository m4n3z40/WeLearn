<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('home')
                       ->appendJSImport('home.js');
    }

    public function index()
    {
        $usuario=$this->autenticacao->getUsuarioAutenticado();
        $partial_perfil = array('dados_usuario' => $this->autenticacao->getUsuarioAutenticado());
        $this->template->render('usuario/home/index',$partial_perfil);

    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */