<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quickstart extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
        
        $this->template->appendJSImport('logout_usuario.js');
    }

    public function index()
    {
        $this->template->render();
    }
}

/* End of file quickstart.php */
/* Location: ./application/controllers/quickstart.php */