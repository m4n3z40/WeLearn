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
		$this->template->render();
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

    public function recuperar_lista($area = '')
    {
        if( ! $this->input->is_ajax_request()) {
            show_404();
        }
        
		set_json_header();

        if ($area) {
            try {
                $segmentoDao = WeLearn_DAO_DAOFactory::create('SegmentoDAO');

                $opcoes = array('areaId' => $area);

                $listaSegmentos = $segmentoDao->recuperarTodos('', '', $opcoes);

                $dropdownSegmentos = array();
                foreach ($listaSegmentos as $segmento) {
                    $dropdownSegmentos[] = $segmento->toCassandra();;
                }

                $segmentosJSON = Zend_Json::encode($dropdownSegmentos);
                
                echo '{"success":true, "segmentos":' . $segmentosJSON . '}';

            } catch(cassandra_NotFoundException $e) {
                $error_msg =  'Esta área ainda não possui segmentos,'
                            .' sinta-se a vontade para adicionar um segmento você mesmo!';

                $error = create_json_feedback_error_json($error_msg, 'area');

                echo create_json_feedback(false, $error);
            } catch(Exception $e) {
                log_message('error', 'Erro ao recuperar lista de segmentos: '. create_exception_description($e));

                $error = create_json_feedback_error_json('Ops! ocorreu um erro. Tente novamente em breve.');

                echo create_json_feedback(false, $error);
            }
        }
    }
}

/* End of file segmento.php */
/* Location: ./application/controllers/segmento.php */