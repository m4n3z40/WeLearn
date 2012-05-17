<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Home_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('home.js');
    }

    public function index()
    {
        $partialCriarFeed= $this->template->loadPartial(
            'form',
             array(),
            'usuario/feed'
        );
        $partialListarFeed= $this->template->loadPartial(
            'lista',
            array(),
            'usuario/feed'
        );
        $dados= array('criarFeed' => $partialCriarFeed , 'listarFeed' => $partialListarFeed);
        $this->_renderTemplateHome('usuario/feed/index',$dados);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */