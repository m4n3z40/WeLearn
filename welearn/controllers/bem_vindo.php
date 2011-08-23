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
        }
        
        $this->template->appendJSImport('login_usuario.js');
        $this->template->appendJSImport('cadastro_usuario.js');
    }

    public function index()
    {
        $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
        $listaAreaObjs = $areaDao->recuperarTodos();
        
        $this->load->helper('area');
        $listaAreas = lista_areas_para_dados_dropdown($listaAreaObjs);
        
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
        
        $this->template->render('usuario/cadastrar', $partial_cadastro);
    }
}

/* End of file bem_vindo.php */
/* Location: ./application/controllers/bem_vindo.php */
