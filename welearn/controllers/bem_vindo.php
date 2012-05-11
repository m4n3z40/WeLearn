<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bem_vindo extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    public function __construct()
    {
        parent::__construct();
        
        if ( $this->autenticacao->isAutenticado() ) {
            redirect('/home');
        }
    }

    public function index()
    {
        $this->load->helper('area');
        $listaAreas = lista_areas_para_dados_dropdown();

        $dadosPartial = array(
            'listaAreas' => $listaAreas
        );

        $partial_cadastro = array(
            'form_cadastro' => $this->template
                                    ->loadPartial(
                                            'form_cadastro',
                                            $dadosPartial,
                                            'usuario'
                                    )
        );

        $this->template->appendJSImport('cadastro_usuario.js');

        $this->_renderTemplate('usuario/cadastrar', $partial_cadastro);
    }
}

/* End of file bem_vindo.php */
/* Location: ./application/controllers/bem_vindo.php */
