<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sugestao extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
    }

	public function index()
	{
		echo 'Curso Módulo';
    }
}

/* End of file sugestao.php */
/* Location: ./application/controllers/curso/sugestao.php */