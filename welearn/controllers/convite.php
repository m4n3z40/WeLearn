<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 25/04/12
 * Time: 17:33
 * To change this template use File | Settings | File Templates.
 */
class Convite extends Home_Controller
{
    private static $_count=30;
    public function __construct(){
        parent::__construct();
        $this->template->appendJSImport('home.js')
                       ->appendJSImport('convite.js');
    }

    public function index($param)
    {
        try{
            if( $param != 'enviados' && $param != 'recebidos') {
                redirect(site_url('usuario/amigos'));
            }else{
             try{
                $conviteCadastradoDao= WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
                $filtros=array('usuarioObj' => $this->autenticacao->getUsuarioAutenticado(),'count' => self::$_count+1,'tipoConvite' => $param);
                $listaConvites=$conviteCadastradoDao->recuperarTodos('','',$filtros);
             }catch(cassandra_NotFoundException $e)
             {
                 $listaConvites=array();
             }
                $this->load->helper('paginacao_cassandra');
                $dadosPaginados = create_paginacao_cassandra($listaConvites, self::$_count);
                $listaConvites= array_reverse($listaConvites);
                $partialListaConvites = $this->template->loadPartial(
                    'lista',
                    array(
                        'listaConvites' => $listaConvites,
                        'paginacao' => $dadosPaginados,
                        'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                        'haConvites' => !empty($listaConvites),
                        'haMaisPaginas' => $dadosPaginados['proxima_pagina'],
                        'tipo'=>$param
                    ),
                    'usuario/convite'
                );
                $dadosView = array('partialListaConvites' => $partialListaConvites,'idUsuario' => $this->autenticacao->getUsuarioAutenticado()->getId());
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
                try{
                $conviteCadastradoDao= WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
                $filtros=array('usuarioObj' => $this->autenticacao->getUsuarioAutenticado(),'count' => self::$_count+1,'tipoConvite' => $param);
                $listaConvites=$conviteCadastradoDao->recuperarTodos($inicio,'',$filtros);
                }catch(cassandra_NotFoundException $e)
                {
                    $listaConvites=array();
                }
                $this->load->helper('paginacao_cassandra');
                $dadosPaginados = create_paginacao_cassandra($listaConvites, self::$_count);
                $listaConvites = array_reverse($listaConvites);
                $response=array('success' => true,'paginacao' => $dadosPaginados,'htmlListaConvites' =>
                $partialListaConvites = $this->template->loadPartial(
                    'lista',
                    array(
                        'listaConvites' => $listaConvites,
                        'paginacao' => $dadosPaginados,
                        'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                        'haConvites' => !empty($listaConvites),
                        'haMaisPaginas' => $dadosPaginados['proxima_pagina'],
                        'tipo'=>$param
                    ),
                    'usuario/convite'
                ));
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
                $saoAmigos=$amizadeUsuarioDao->saoAmigos($remetente,$destinatario);

                if($saoAmigos == WeLearn_Usuarios_StatusAmizade::NAO_AMIGOS)
                {
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

                    //enviar notificação ao usuário;
                    $notificacao = new WeLearn_Notificacoes_NotificacaoRequisicaoAmizade();
                    $notificacao->setConvite( $conviteCadastrado );
                    $notificacao->setDestinatario( $destinatario );
                    $notificacao->adicionarNotificador( new WeLearn_Notificacoes_NotificadorCassandra() );
                    $notificacao->adicionarNotificador( new WeLearn_Notificacoes_NotificadorTempoReal() );
                    $notificacao->notificar();
                    //fim da notificação;

                }else if($saoAmigos == WeLearn_Usuarios_StatusAmizade::REQUISICAO_EM_ESPERA){

                    $this->load->helper('notificacao_js');
                    $json = Zend_Json::encode( array( 'success' => true, 'amigos'=>true));
                    $notificacoesFlash = create_notificacao_json(
                        'aviso',
                        'Voce Recebeu um Convite de '.$destinatario->getNome().'!'
                    );
                    $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
                }

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

    public function remover($idConvite,$idRemetente,$idDestinatario,$view)
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

            if($view == 'perfil'){
                $result= array('success'=>true);
                $notificacoesFlash = create_notificacao_json(
                    'sucesso',
                    'Convite Removido com sucesso!'
                );
            }
            if($view == 'lista')
            {
                $result= array('success'=>true,'notificacao'=> create_notificacao_array(
                    'sucesso',
                    'Convite Removido com sucesso!'
                ));
            }

        }catch(cassandra_NotFoundException $e)
        {
            $amizadeDao=WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
            $usuarioDao=WeLearn_DAO_DAOFactory::create('UsuarioDAO');
            $Remetente=$usuarioDao->recuperar($idRemetente);
            $Destinatario=$usuarioDao->recuperar($idDestinatario);
            $UsuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
            $amizadeObj=$amizadeDao->criarNovo();
            $amizadeObj->setUsuario( $Destinatario);
            $amizadeObj->setAmigo( $Remetente);
            $saoAmigos=$amizadeDao->saoAmigos($amizadeObj->getUsuario(),$amizadeObj->getAmigo());
            $usuario=($UsuarioAutenticado == $Destinatario) ? $Remetente : $Destinatario ;
            if($view == 'perfil'){
                if($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS){
                    $result = array('success'=>false,'amigos'=>true);
                    $notificacoesFlash = create_notificacao_json(
                        'erro',
                        'O Convite Já foi Aceito Por '. $usuario->getNome().'!'
                    );
                }else{
                    $result = array('success' => false, 'amigos' => false);
                    $notificacoesFlash = create_notificacao_json(
                        'erro',
                        'O Convite Já Foi Removido Por '.$usuario->getNome().'!'
                    );
                }
            }
            if($view == 'lista'){
                if($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS){
                    $result = array('success'=>false,'notificacao'=> create_notificacao_array(
                        'erro',
                        'O Convite já foi Aceito por '.$usuario->getNome().'!'
                    ));

                }else{
                    $result = array('success'=>false,'notificacao' => create_notificacao_array(
                        'erro',
                        'O Convite já foi Removido por '.$usuario->getNome().'!'
                    ));

                }
            }


        }
        if($view == 'perfil'){
            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
        }
        echo Zend_Json::encode($result);
    }



    public function aceitar($idConvite,$idRemetente,$idDestinatario,$view)
    {
        $this->load->helper('notificacao_js');
        $conviteCadastradoDao = WeLearn_DAO_DAOFactory::create('ConviteCadastradoDAO');
        $amizadeDao = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        try{
           $conviteRemovido = $conviteCadastradoDao->remover($idConvite);
           $idAmizade = $amizadeDao->gerarIdAmizade($conviteRemovido->getDestinatario(), $conviteRemovido->getRemetente());
           $amizadeObj = $amizadeDao->recuperar($idAmizade);
           $amizadeObj->setStatus(WeLearn_Usuarios_StatusAmizade::AMIGOS);
           $amizadeDao->salvar($amizadeObj);
           if($view == 'perfil'){
               $result = array('success' => true);
               $notificacoesFlash = create_notificacao_json(
                   'sucesso',
                   'Convite Aceito!'
               );
           }
           if($view == 'lista'){
               $result = array('success' => true, 'notificacao' => create_notificacao_array(
                   'sucesso',
                   'Convite Aceito!'
               ));
           }

           //enviar notificação ao usuário;
           $notificacao = new WeLearn_Notificacoes_NotificacaoRequisicaoAmizadeAceita();
           $notificacao->setConvite( $conviteRemovido );
           $notificacao->setDestinatario( $conviteRemovido->getRemetente() );
           $notificacao->adicionarNotificador( new WeLearn_Notificacoes_NotificadorCassandra() );
           $notificacao->adicionarNotificador( new WeLearn_Notificacoes_NotificadorTempoReal() );
           $notificacao->notificar();
           //fim da notificação;

        }catch(cassandra_NotFoundException $e){
           $usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
           $Remetente = $usuarioDao->recuperar($idRemetente);
           $Destinatario = $usuarioDao->recuperar($idDestinatario);
           $UsuarioAutenticado = $this->autenticacao->getUsuarioAutenticado();
           $amizadeObj = $amizadeDao->criarNovo();
           $amizadeObj->setUsuario( $Destinatario);
           $amizadeObj->setAmigo( $Remetente);
           $saoAmigos = $amizadeDao->saoAmigos($amizadeObj->getUsuario(), $amizadeObj->getAmigo());

           if($view == 'perfil'){
               $usuario=($UsuarioAutenticado == $Destinatario) ? $Remetente : $Destinatario ;
               if($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS){
                   $result = array('success' => false, 'amigos' => true);
                   $notificacoesFlash = create_notificacao_json(
                       'erro',
                       'O Convite Já foi Aceito '.$usuario->getNome()
                   );
               }else{
                   $result = array('success' => false, 'amigos' => false);
                   $notificacoesFlash = create_notificacao_json(
                       'erro',
                       'O Convite Foi Removido Por '.$usuario->getNome()
                   );
               }
           }
           if($view == 'lista'){
               $usuario=($UsuarioAutenticado == $Destinatario) ? $Remetente : $Destinatario ;
               if($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS){
                   $result = array('success' => false, 'notificacao' => create_notificacao_array(
                       'erro',
                       'O Convite Já foi Aceito Por '.$usuario->getNome()
                   ));
               }else{
                   $result = array('success' => false, 'notificacao' => create_notificacao_array(
                       'erro',
                       'O Convite Foi Removido por '.$usuario->getNome()
                   ));
               }
           }
        }
        if($view == 'perfil'){
            $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
        }
        echo Zend_Json::encode($result);
    }

    protected function _renderTemplateHome($view = '', $dados = array())
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                $dados,
                'usuario/amigos'
            )
        );

        parent::_renderTemplateHome($view, $dados);
    }

}