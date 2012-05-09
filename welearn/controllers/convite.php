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
                $listaConvites= array_reverse($listaConvites);
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
        $this->_renderTemplateHome('usuario/convite/listar', $dadosView);

    }



    public function proxima_pagina($param,$inicio)
    {

        try{
            if( $param != 'enviados' && $param != 'recebidos') {
                redirect(site_url('usuario/amigos'));
            }else{
                $count=10;
                $conviteCadastradoDao= WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
                $filtros=array('usuarioObj' => $this->autenticacao->getUsuarioAutenticado(),'count' => $count+1,'tipoConvite' => $param);
                $listaConvites=$conviteCadastradoDao->recuperarTodos($inicio,'',$filtros);
                $this->load->helper('paginacao_cassandra');
                $dadosPaginados = create_paginacao_cassandra($listaConvites, $count);
                $listaConvites = array_reverse($listaConvites);
                $response=array('success' => true,'paginacao' => $dadosPaginados,'htmlListaConvites' =>
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
                )
                );
                $json=Zend_Json::encode($response);
            }
        }catch(UUIDException $e)
        {
            log_message(
                'error',
                'Ocorreu um erro ao tentar recupera uma nova página de convites: '
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





    public function enviar(){
        $this->load->library('form_validation');

        if ($this->form_validation->run() === FALSE) {

            $json = create_json_feedback(false, validation_errors_json());

            exit($json);
        }else{
            try{
                $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
                $amizadeUsuarioDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
                $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');

                $mensagem = $this->input->post('txt-convite');
                $remetente = $this->autenticacao->getUsuarioAutenticado();
                $destinatario = $usuarioDao->recuperar( $this->input->post('destinatario') );

                $conviteCadastrado = $conviteCadastradoDao->criarNovo();
                $conviteCadastrado->setMsgConvite($mensagem);
                $conviteCadastrado->setRemetente($remetente);
                $conviteCadastrado->setDestinatario($destinatario);
                $conviteCadastrado->setStatus(WeLearn_Convites_StatusConvite::EM_ESPERA_NOVO);
                $conviteCadastrado->setDataEnvio(time());

                $conviteCadastradoDao->salvar($conviteCadastrado);

                $amizadeUsuarioObj = $amizadeUsuarioDao->criarNovo();
                $amizadeUsuarioObj->setUsuario( $remetente );
                $amizadeUsuarioObj->setAmigo( $destinatario );
                $amizadeUsuarioObj->setStatus( WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA );

                $amizadeUsuarioDao->salvar($amizadeUsuarioObj);

                $json = Zend_Json::encode( array( 'success' => true ) );

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





    public function remover($idConvite)
    {
        $this->load->helper('notificacao_js');
        try{
            $conviteCadastradoDao=WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
            $conviteRemovido=$conviteCadastradoDao->remover($idConvite);

            $amizadeDao=WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
            $amizadeObj=$amizadeDao->criarNovo();
            $amizadeObj->setUsuario( $conviteRemovido->getDestinatario() );
            $amizadeObj->setAmigo( $conviteRemovido->getRemetente());
            $idAmizade=$amizadeDao->gerarIdAmizade($amizadeObj->getUsuario(),$amizadeObj->getAmigo());
            $amizadeRemovida=$amizadeDao->remover($idAmizade);
            $result= array('success'=>true,'notificacao'=> create_notificacao_array(
                'sucesso',
                'convite removido'
            ));
        }catch(cassandra_NotFoundException $e)
        {
            $result=array('success'=>false,'notificacao'=> create_notificacao_array(
                'erro',
                'falha ao remover convite'
            ));

        }
        echo Zend_Json::encode($result);
    }

    public function aceitar($idConvite){
       $this->load->helper('notificacao_js');
       try{
           $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
           $conviteRemovido = $conviteCadastradoDao->remover($idConvite);
           $amizadeDao=WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
           $idAmizade=$amizadeDao->gerarIdAmizade($conviteRemovido->getDestinatario(),$conviteRemovido->getRemetente());
           $amizadeObj=$amizadeDao->recuperar($idAmizade);
           $amizadeObj->setPersistido(true);
           $amizadeObj->setStatus(WeLearn_Usuarios_StatusAmizade::AMIGOS);
           $amizadeDao->salvar($amizadeObj);
           $result= array('success'=>true,'notificacao'=> create_notificacao_array(
               'sucesso',
               'convite aceito'
           ));

       }catch(cassandra_NotFoundException $e){
           $result=array('success'=>false,'notificacao'=> create_notificacao_array(
               'erro',
               'falha ao aceitar convite'
           ));
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