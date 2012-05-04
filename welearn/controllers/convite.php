<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 25/04/12
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */
class Convite extends WL_Controller
{
    public function __construct(){
        parent::__construct();
        $this->template->setTemplate('home')
            ->appendJSImport('home.js')
            ->appendJSImport('convite.js');
    }

    public function index($param){
        try{
            if( $param != 'enviados' && $param != 'recebidos') {
                redirect(site_url('usuario/amigos'));
            }else{
                $count=10;
                $conviteCadastradoDao= WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
                $filtros=array('usuarioObj' => $this->autenticacao->getUsuarioAutenticado(),'count' => $count+1,'tipoConvite' => $param);
                $listaConvites=$conviteCadastradoDao->recuperarTodos('','',$filtros);
                $this->load->helper('paginacao_cassandra');
                $dadosPaginados = create_paginacao_cassandra($listaConvites, $count);
                $partialListaConvites = $this->template->loadPartial(
                    'lista',
                    array(
                        'success'=>true,
                        'convites' => $listaConvites,
                        'paginacao' => $dadosPaginados,
                        'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                        'haConvites' => $dadosPaginados['proxima_pagina'],
                        'tipo'=>$param
                    ),
                    'usuario/convite'
                );
                $dadosView = array(
                    'partialListaConvites' => $partialListaConvites

                );
            }
        }catch(cassandra_NotFoundException $e)
        {
          $dadosView['success'] =false;
        }
        $this->_renderTemplateHome('usuario/convite/index', $dadosView);

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
                $conviteCadastrado->setDataEnvio(time());
                $conviteCadastradoDao->salvar($conviteCadastrado);
                $amizadeUsuarioDao=WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
                $amizadeUsuarioObj=$amizadeUsuarioDao->criarNovo(array('convite'=>$conviteCadastrado));
                $amizadeUsuarioDao->salvar($amizadeUsuarioObj);
                $response=array('success'=>true);
                $json = Zend_Json::encode($response);
                $this->load->helper('notificacao_js');
                $notificacoesFlash = create_notificacao_json(
                    'sucesso',
                    'Convite enviado com sucesso!'
                );
                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
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

                $json = create_json_feedback(false, $e);
            }

            echo $json;
        }

    }



    public function remover($id,$idRemetente)
    {
        try{
            $conviteCadastradoDao=WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
            $destinatario=$this->autenticacao->getUsuarioAutenticado();
            $conviteCadastradoDao->remover($id);

            $result= array('success'=>true);
        }catch(cassandra_NotFoundException $e)
        {
            $result=array('success'=>false);

        }
        echo Zend_Json::encode($result);
    }

    private function _renderTemplateHome($view = '', $dados = array())
    {
        $dadosBarraEsquerda = array(
            'usuario' => $this->autenticacao->getUsuarioAutenticado()
        );

        $dadosBarraDireita = array(
            'menuContexto' => $this->template->loadPartial('menu', array(), 'usuario/convite')
        );

        $this->template->setDefaultPartialVar('home/barra_lateral_esquerda', $dadosBarraEsquerda)
            ->setDefaultPartialVar('home/barra_lateral_direita', $dadosBarraDireita)
            ->render($view, $dados);
    }




}
