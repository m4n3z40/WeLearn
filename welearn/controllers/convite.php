<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 25/04/12
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */
class Convite extends CI_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){

    }

    public function enviar(){
        $this->load->library('form_validation');

        if ($this->form_validation->run() === FALSE) {

            $json = create_json_feedback(false, validation_errors_json());

            exit($json);
        }else{
            try{
                $mensagem=$this->input->post('txt-convite');
                $remetente=$this->autenticacao->getUsuarioAutenticado();
                $destinatario=$this->input->post('destinatario');
                $conviteCadastradoDao=WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
                $destinatario=WeLearn_DAO_DAOFactory::create('UsuarioDAO')->recuperar($destinatario);
                $conviteCadastrado=$conviteCadastradoDao->criarNovo();
                $conviteCadastrado->setMsgConvite($mensagem);
                $conviteCadastrado->setRemetente($remetente);
                $conviteCadastrado->setDestinatario($destinatario);
                $conviteCadastrado->setStatus(0);
                $conviteCadastradoDao->salvar($conviteCadastrado);
                $response=array('success'=>true);
                $json = Zend_Json::encode($response);
            }catch(Exception $e){
                log_message(
                    'error',
                    'Ocorreu um erro ao tentar enviar o convite '
                        . create_exception_description($e)
                );

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro inesperado, já estamos verificando.
                Tente novamente mais tarde.'
                );

                $json = create_json_feedback(false, $error);
            }

            echo $json;
        }

    }




}
