<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 28/03/12
 * Time: 19:13
 * To change this template use File | Settings | File Templates.
 */
class Mensagem extends Home_Controller
{
    private static $_count=30;
    function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('home.js')
                       ->appendJSImport('mensagem.js');
    }

    public function index()
    {
        $mensagemDao = WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');

        try {
            $dados = $mensagemDao->recuperarListaAmigosMensagens(
                $this->autenticacao->getUsuarioAutenticado()
            );

            $dadosView = array(
                'mensagens' => $dados,
                'success'=> true
            );

        } catch (Exception $e) {
            $dadosView=array( 'success' => false );
        }

        $this->_renderTemplateHome('usuario/mensagem/index', $dadosView);
    }

    public function listar($idAmigo='')
    {

        try{

            if( $idAmigo=='' ) {
                redirect($this->index());
            }else{
                $usuario = $this->autenticacao->getUsuarioAutenticado();
                $amigo = WeLearn_DAO_DAOFactory::create('UsuarioDAO')->recuperar($idAmigo);
                $mensagemDao = WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
                $filtros= array('count' => self::$_count+1,'usuario' => $usuario, 'amigo' => $amigo);

                try {
                    $listaMensagens = $mensagemDao->recuperarTodos('','',$filtros);


                } catch (cassandra_NotFoundException $e) {
                    $listaMensagens = array();
                }

                $this->load->helper('paginacao_cassandra');
                $dadosPaginados = create_paginacao_cassandra($listaMensagens, self::$_count);
                $dadosPaginados= array_reverse($dadosPaginados);

                $partialListaMensagens = $this->template->loadPartial(
                    'lista',
                    array(
                        'mensagens' => $listaMensagens,
                        'paginacao' => $dadosPaginados,
                        'amigo'=>$amigo,
                        'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina'],
                        'haMensagens' => $dadosPaginados['proxima_pagina']
                    ),
                    'usuario/mensagem'
                );

                $partialEnviarMensagem = $this->template->loadPartial(
                    'criar',
                    array( 'idDestinatario' => $idAmigo ),
                    'usuario/mensagem'
                );

                $dadosView = array(
                    'listaMensagens' => $partialListaMensagens,
                    'enviarMensagem' => $partialEnviarMensagem
                );

                $this->_renderTemplateHome('usuario/mensagem/listar', $dadosView);
            }
        }catch(Exception $e) {
            log_message('error', 'Erro ao tentar exibir lista de mensagens: '
                . create_exception_description($e));

            show_404();
        }

    }


    public function proxima_pagina($idAmigo, $inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        try{
            $usuario = $this->autenticacao->getUsuarioAutenticado();
            $amigo = WeLearn_DAO_DAOFactory::create('UsuarioDAO')->recuperar($idAmigo);
            $mensagemDao = WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');
            $filtros= array('count' => self::$_count+1,'usuario' => $usuario, 'amigo' => $amigo);
            try {
                $listaMensagens = $mensagemDao->recuperarTodos($inicio,'',$filtros);
            } catch(cassandra_NotFoundException $e) {
                $listaMensagens= array();
            }

            $this->load->helper('paginacao_cassandra');
            $dadosPaginados = create_paginacao_cassandra($listaMensagens,self::$_count);
            $dadosPaginados= array_reverse($dadosPaginados);

            $response = array(
                'success' => true,
                'htmlListaMensagens' => $this->template->loadPartial(
                    'lista',
                    array(
                        'idAmigo' => $idAmigo,
                        'haMensagens'=> !empty($dadosPaginados),
                        'mensagens' => $listaMensagens,
                        'inicioProxPagina' => $dadosPaginados['inicio_proxima_pagina']
                    ),
                    'usuario/mensagem'
                ),
                'paginacao' => $dadosPaginados
            );

            $json = Zend_Json::encode($response);

        } catch (UUIDException $e) {

            log_message(
                'error',
                'Ocorreu um erro ao tentar recupera uma nova página de mensagens: '
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

    public function criar()
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

        $mensagem = $this->input->post('mensagem');
        $idDestinatario = $this->input->post('destinatario');

        $remetente = $this->autenticacao->getUsuarioAutenticado();
        $destinatario = WeLearn_DAO_DAOFactory::create('UsuarioDAO')->recuperar($idDestinatario);

        $mensagemDao = WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');

        $mensagemObj = $mensagemDao->criarNovo();
        $mensagemObj->setMensagem($mensagem);
        $mensagemObj->setDestinatario($destinatario);
        $mensagemObj->setRemetente($remetente);
        $mensagemObj->setStatus(WeLearn_Usuarios_StatusMP::NOVO);
        $mensagemDao->salvar($mensagemObj);

        $this->load->helper('notificacao_js');

        $response = array(
            'success' => true,
            'mensagemId'=>$mensagemObj->getId(),
            'remetente'=>$mensagemObj->getRemetente()->toHTML('imagem_pequena'),
            'mensagemTexto'=>$mensagemObj->getMensagem(),
            'dataEnvio'=>date('d/m/Y à\s H:i',$mensagemObj->getDataEnvio()),
            'notificacao'=> create_notificacao_array(
                'sucesso',
                'Mensagem enviada com sucesso'
            )
        );

        $json = Zend_Json::encode($response);

        echo $json;

        //enviar notificação ao usuário;
        $notificacao = new WeLearn_Notificacoes_NotificacaoMensagemPessoal();
        $notificacao->setMensagemPessoal( $mensagemObj );
        $notificacao->setDestinatario( $destinatario );
        $notificacao->adicionarNotificador( new WeLearn_Notificacoes_NotificadorCassandra() );
        $notificacao->notificar();
        //fim da notificação;
    }


    public function remover($idMensagem,$idAmigo)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();
        $this->load->helper('notificacao_js');
        try{
            $usuario = $this->autenticacao->getUsuarioAutenticado();
            $amigo = WeLearn_DAO_DAOFactory::create('UsuarioDAO')->recuperar($idAmigo);;

            $mensagemPessoalDao = WeLearn_DAO_DAOFactory::create('MensagemPessoalDAO');

            $mensagemPessoal = $mensagemPessoalDao->criarNovo();
            $mensagemPessoal->setDestinatario($amigo);
            $mensagemPessoal->setRemetente($usuario);

            $mensagemPessoalDao->remover($idMensagem);
            $json=Zend_Json::encode(array( 'success' => true , 'notificacao'=> create_notificacao_array(
                'sucesso',
                'Mensagem Removida Com Sucesso!'
            )
            ));

        } catch( Exception $e ) {

            $json=Zend_Json::encode(array( 'success' => false , 'notificacao'=> create_notificacao_array(
                'erro',
                'A Mensagem Selecionada Não Existe, ou Já Foi Removida!'
            )
            ));
        }

        echo $json;
    }
}

