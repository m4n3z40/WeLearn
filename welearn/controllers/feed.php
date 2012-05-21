<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 16/05/12
 * Time: 17:37
 * To change this template use File | Settings | File Templates.
 */
class Feed extends Home_Controller
{

    function __construct()
    {
        parent::__construct();
    }
    public function index()
    {

    }

    public function criar()
    {

        set_json_header();
        $tipo=$this->input->post('tipo-feed');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('conteudo-feed', 'conteudo-feed', 'required');
        if($tipo != WeLearn_Compartilhamento_TipoFeed::STATUS)
        {
            $this->form_validation->set_rules('descricao-feed', 'descricao-feed', 'callback_validar_descricao');
        }

        if($this->form_validation->run()===false)
        {
            $json = create_json_feedback(false, validation_errors_json());
            exit($json);
        }


        $feedDao = WeLearn_DAO_DAOFactory::create('FeedDAO');
        $feedUsuario = $feedDao->criarNovo();
        $criador=$this->autenticacao->getUsuarioAutenticado();
        $conteudo=$this->input->post('conteudo-feed');


        if($tipo != WeLearn_Compartilhamento_TipoFeed::STATUS)
        {
            $descricao=$this->input->post('descricao-feed');
            $feedUsuario->setDescricao($descricao);
        }


        $feedUsuario->setConteudo($conteudo);
        $feedUsuario->setTipo($tipo);
        $feedUsuario->setCriador($criador);
        $feedUsuario->setDataEnvio(time());
        $this->load->helper('notificacao_js');
        try{
            $feedDao->salvar($feedUsuario);
            $response = array('notificacao' => create_notificacao_array('sucesso','Feed Adicionado com Sucesso'));
            $json = create_json_feedback(true,'',$response);
        }catch(cassandra_NotFoundException $e){
            $json=create_json_feedback(false);
        }

        echo $json;
    }

    public function validar_descricao($str)
    {
        if (is_null($str))
        {
            $this->form_validation->set_message('validar_descricao', 'The %s field is required');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

}
