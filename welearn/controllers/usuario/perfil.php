<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Perfil extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('perfil');
    }

    public function index()
    {
        $this->template->render('usuario/perfil/index');
    }
}

/* End of file perfil.php */
/* Location: ./application/controllers/usuario/perfil.php */