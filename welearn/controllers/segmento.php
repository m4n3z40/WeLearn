<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Segmento extends WL_Controller {

    /**
     * Construtor carrega configurações da classes base CI_Controller
     * (Resolve bug ao utilizar this->load)
     */
    function __construct()
    {
        parent::__construct();
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