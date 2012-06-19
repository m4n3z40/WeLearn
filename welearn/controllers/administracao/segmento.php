<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento extends Home_Controller{

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
		$this->_renderTemplateHome();
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
            $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

            $dadosNovoSegmento = array(
                'area' => $areaDao->recuperar($this->input->post('areaId')),
                'descricao' => $this->input->post('descricao')
            );
            $novoSegmento = $segmentoDao->criarNovo($dadosNovoSegmento);

            $segmentoDao->salvar($novoSegmento);

            $json = create_json_feedback(true);
            exit($json);

        } catch (Exception $e) {
            log_message('error', 'Erro ao adicionar segmento. ' . create_exception_description($e));

            $errors = create_json_feedback_error_json(
                'Ocorreu um erro ao adicionar o novo segmento.<br/>'
               .'Já estamos verificando, tente novamente em breve.'
            );

            $json = create_json_feedback(false, $errors);
            exit($json);
        }
    }

    protected function _renderTemplateHome($view = '', $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial( 'menu', Array(), 'administracao' )
        );

        parent::_renderTemplateHome($view, $dados);
    }

    public function criar(){

        try{
            $dadosFormCriar = array(
                'descricaoAtual'=>''
                );
            $dadosViewCriar = array(
                'formAction' => 'administracao/segmento/salvar',
                'extraOpenForm' => 'id="form-criar-segmento"',
                'hiddenFormData' => array('acao' => 'criar'),
                'formCriar' => $this->template->loadPartial('form', $dadosFormCriar, 'administracao/segmento'),

            );
            $this->_renderTemplateHome('administracao/segmento/criar', $dadosViewCriar);
        }catch (Exception $e){
            log_message('erro','Erro ao exibir formulario de criação de segmento: ' . create_exception_description($e));

            show_404();

        }

    }

    public function recuperar_lista($areaId){
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {

            $areaDAO = WeLearn_DAO_DAOFactory::create('AreaDAO');
            $area = $areaDAO->recuperar($areaId);

            $segmentoDAO = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
            $filtro=array();
            $segmentos = $segmentoDAO->recupertarTodos($de='',$ate='',$filtro);




            $response = array(
                'success' => true,
                'listaSegmento' => $this->template->loadPartial(
                    'lista',
                    array(
                        'segmentos'=>$segmentos
                    ),
                    'administracao/segmento'
                )

            );

            $json = Zend_Json::encode($response);

        }catch (Exception $e){
            log_message('error', 'Ocorreu um erro ao tentar recuperar os
                                    Segmentos via ajax: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);

        }

        echo $json;
    }

    public function salvar(){


        set_json_header();
        $this->load->helper('notificacao_js');
        $this->load->library('form_validation');
        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
            echo $json;
        } else {
            try{

                $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
                $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

                $dadosNovoSegmento = array(
                    'area' => $areaDao->recuperar($this->input->post('areaId')),
                    'descricao' => $this->input->post('descricao')
                );
                $novoSegmento = $segmentoDao->criarNovo($dadosNovoSegmento);

                $segmentoDao->salvar($novoSegmento);

                $notificacoesFlash = create_notificacao_json(
                    'sucesso',
                    'O novo Segmento foi criada com sucesso.',
                    10000
                );
                $this->session->set_flashdata('notificacoesFlash',$notificacoesFlash);
                $json = create_json_feedback(true, '', '"idSegmento":"' . $novoSegmento->getDescricao() . '"');

            }catch (Exception $e) {
                log_message('error', 'Erro a criar a Área: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ocorreu um erro inesperado! Já estamos verificando, tente novamente mais tarde.');
                $json = create_json_feedback(false, $error);
            }
            echo $json;
        }
    }



}

/* End of file segmento.php */
/* Location: ./application/controllers/adminstracao/segmento.php */