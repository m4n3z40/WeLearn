<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 11/06/12
 * Time: 14:21
 * To change this template use File | Settings | File Templates.
 */
class notificacao extends Home_Controller
{
    /**
     * @var NotificacaoDAO
     */
    private $_notificacaoDao;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_usuarioAtual;

    public function __construct()
    {
        parent::__construct();

        $this->_notificacaoDao = WeLearn_DAO_DAOFactory::create('NotificacaoDAO');
        $this->_usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

        $this->template->appendJSImport('notificacao.js');
    }

    public function index()
    {
        $this->listar();
    }

    public function listar()
    {
        try {
            $count = 20;

            try {
                $listaNotificacoes = $this->_notificacaoDao->recuperarTodosPorUsuario(
                    $this->_usuarioAtual,
                    '',
                    '',
                    $count + 1
                );

                $totalNotificacoesNovas = $this->_notificacaoDao->recuperarQtdTotalNovasPorUsuario(
                    $this->_usuarioAtual
                );
            } catch (cassandra_NotFoundException $e) {
                $listaNotificacoes = array();

                $totalNotificacoesNovas = 0;
            }

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra( $listaNotificacoes, $count );

            $dadosView = array(
                'haNotificacoes' => count( $listaNotificacoes ) > 0,
                'totalNotificacoesNovas' => $totalNotificacoesNovas,
                'listaNotificacoes' => $this->template->loadPartial(
                    'lista',
                    array( 'listaNotificacoes' => $listaNotificacoes ),
                    'notificacao'
                ),
                'haProxPagina' => $paginacao['proxima_pagina'],
                'inicioProxPagina' => $paginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateHome('notificacao/listar', $dadosView);

            if ( $totalNotificacoesNovas > 0 ) {
                $this->_notificacaoDao->limparNovas( $this->_usuarioAtual );
            }
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar listar notificações de usuário: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function proxima_pagina($inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 20;

            try {
                $listaNotificacoes = $this->_notificacaoDao->recuperarTodosPorUsuario(
                    $this->_usuarioAtual,
                    $inicio,
                    '',
                    $count + 1
                );
            } catch (cassandra_NotFoundException $e) {
                $listaNotificacoes = array();
            }

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra( $listaNotificacoes, $count );

            $response = Zend_Json::encode(array(
                'htmlListaNotificacoes' => $this->template->loadPartial(
                    'lista',
                    array( 'listaNotificacoes' => $listaNotificacoes ),
                    'notificacao'
                ),
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);

        } catch (Exception $e) {
            log_message('error', 'Erro ao tentat listar próxima página de notificações de usuário: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro desconhecido, já estamos verificando. Tente novamente mais tarde.'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }
}
