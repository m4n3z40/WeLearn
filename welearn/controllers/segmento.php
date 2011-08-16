<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento extends CI_Controller {

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
		$this->template->setTitle('Welcome to CodeIgniter')
                       ->render('welcome_message');
    }

    public function adicionar()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        header('Content-type: applcation/json');

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
}

/* End of file segmento.php */
/* Location: ./application/controllers/segmento.php */