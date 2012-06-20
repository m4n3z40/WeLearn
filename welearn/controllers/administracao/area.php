<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Area extends Home_Controller {

     private $_areaDAO;
    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();

        $this->_areaDAO = WeLearn_DAO_DAOFactory::create('AreaDAO');
        $this->template->appendJSImport('area.js');
    }

	public function index()
	{
        //$partialCriar = $this->template->loadPartial('form', array(),'administracao/area');
        //$this->_renderTemplateHome();
        $this->listar();
    }

    public function adicionar()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');
        if ($this->form_validation->run() === FALSE) {
            $json = create_json_feedback(false, validation_errors_json());
            exit($json);
        }

        try {
            $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
            $novaArea = $areaDao->criarNovo($this->input->post());

            $areaDao->salvar($novaArea);

            $json = create_json_feedback(true);
            exit($json);

        } catch (Exception $e) {
            log_message('error', 'Erro ao adicionar Área. ' . create_exception_description($e));

            $errors = create_json_feedback_error_json(
                'Ocorreu um erro ao adicionar uma nova Área.<br/>'
               .'Ja estamos verificando. Tente novamente em breve'
            );

            $json = create_json_feedback(false, $errors);
            exit($json);
        }
    }

    public function listar(){

    try {

        $viewCriarSegmento = $this->template->loadPartial('form',array('formAction'=> '/administracao/segmento/salvar','formExtra'=>array('id'=>'form-criar-segmento','name'=>'form-criar-segmento','style'=>'display:none')),'administracao/segmento');

        $listaAreas = $this->_areaDAO->recuperarTodos($de = null, $ate = null, $filtros = array());

        $this->load->helper('paginacao_cassandra');

        $dadosLista = array(
            'listaAreas' => $listaAreas
        );

        $dadosViewListar = array(
            'haAreas' => !empty($listaAreas),
            'listaAreas' => $this->template->loadPartial('lista', $dadosLista, 'administracao/area'),
            'criarSegmento' => $viewCriarSegmento
            );

        $this->_renderTemplateHome('administracao/area/listar', $dadosViewListar);
    } catch (Exception $e) {
        log_message('error', 'Ocorreu um erro ao exibir a lista de Áreas:'
            . create_exception_description($e));

        show_404();
    }
}
    public function proxima_pagina($areaId, $inicio){

    if ( ! $this->input->is_ajax_request() ) {
        show_404();
    }

    set_json_header();

    try {
        $count = 15;

        $area = $this->_areaDao->recuperar($areaId);

        $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
        $listaAreas = $areaDao->recuperarTodos($de = null,$ate = null, $count + 1);

        $this->load->helper('paginacao_cassandra');
        $dados_paginacao = create_paginacao_cassandra($listaCategorias, $count);

        $dadosLista = array(
            'listaAreas' => $listaAreas
        );

        $response = array(
            'success' => true,
            'htmlListaAreas' => $this->template->loadPartial('lista', $dadosLista, 'administracao/area'),
            'paginacao' => $dados_paginacao
        );

        $json = Zend_Json::encode($response);
    } catch (Exception $e) {
        log_message('error', 'Ocorreu um erro ao recuperar outra página de Áreas: '
            . create_exception_description($e));

        $error = create_json_feedback_error_json('Ocorreu um erro inesperado. Já estamos verificando, tente novamente mais tarde.');

        $json = create_json_feedback(false, $error);
    }

    echo $json;
}

    public function criar(){

        try{
            $dadosFormCriar = array(
                'descricaoAtual'=>''
            );
            $dadosViewCriar = array(
                'formAction' => 'administracao/area/salvar',
                'extraOpenForm' => 'id="form-criar-area"',
                'hiddenFormData' => array('acao' => 'criar'),
                'formCriar' => $this->template->loadPartial('form', $dadosFormCriar, 'administracao/area'),
                'textoBotaoSubmit' => 'Criar nova Área!'
            );
            $this->_renderTemplateHome('administracao/area/criar', $dadosViewCriar);

        }catch (Exception $e) {
                log_message('error', 'Erro ao exibir formulário de criação de Area: ' . create_exception_description($e));

                show_404();
            }
    }

    public function salvar(){


        set_json_header();

        $this->load->library('form_validation');
        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
            echo $json;
        } else {
            try{
                $descricaoArea = $this->input->post('descricao');

                $this->load->helper('notificacao_js');

                    $novaArea = $this->_areaDAO->criarNovo();
                    $novaArea->setDescricao($descricaoArea);
                    $this->_areaDAO->salvar($novaArea);

                    $notificacoesFlash = create_notificacao_json(
                        'sucesso',
                        'A nova Área foi criada com sucesso.',
                        10000
                    );
                    $this->session->set_flashdata('notificacoesFlash',$notificacoesFlash);
                    $json = create_json_feedback(true, '', '"idArea":"' . $novaArea->getDescricao() . '"');

            }catch (Exception $e) {
                    log_message('error', 'Erro a criar a Área: ' . create_exception_description($e));

                    $error = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.');
                    $json = create_json_feedback(false, $error);
            }
            echo $json;
        }
    }

}

/* End of file area.php */
/* Location: ./application/controllers/administracao/area.php */