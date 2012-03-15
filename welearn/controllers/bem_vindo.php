<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bem_vindo extends CI_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
        
        if ( $this->autenticacao->isAutenticado() ) {
            redirect('/home');
        } else {
            redirect('/usuario/cadastrar');
        }
    }
}

/* End of file bem_vindo.php */
/* Location: ./application/controllers/bem_vindo.php */
