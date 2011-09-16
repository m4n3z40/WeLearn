<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pais_estado extends CI_Controller {
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
        echo '<pre>';
        $this->load->model('PaisEstadoDAO');

        $estados = $this->PaisEstadoDAO->recuperarEstadosDeUmPaisSimplificado('BR');

        var_dump($estados);
        echo '</pre>';
    }

    public function recuperar_lista_estados($pais = '')
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        if ($pais) {
            try {
                $paisEstadoDao = WeLearn_DAO_DAOFactory::create('PaisEstadoDAO', null, false);
                $estadosJSON = $paisEstadoDao->recuperarEstadosDeUmPaisJSON($pais);
                echo '{"success": true, "estados":' . $estadosJSON . '}';
            } catch (cassandra_NotFoundException $e) {
                $error = create_json_feedback_error_json('O país selecionado não tem estados cadastrados.', 'estado');
                echo create_json_feedback(false, $error);
            } catch (Exception $e) {
                log_message('error', 'Não foi possível retornar a lista de estados: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ops, ocorreu um erro desconhecido. Tente novamente mais tarde.');
                echo create_json_feedback(false, $error);
            }
        }
    }
}

/* End of file pais_estado.php */
/* Location: ./application/controllers/pais_estado.php */