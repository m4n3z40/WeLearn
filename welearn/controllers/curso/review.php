<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review extends Curso_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('review.js');
    }

    public function index ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');

            if ( $resenhaDao->recuperarReputacaoCurso( $curso ) ) {
                $mediaQualidade = $curso->getMediaQualidade();
                $mediaDificuldade = $curso->getMediaDificuldade();
            } else {
                $mediaQualidade = 0;
                $mediaDificuldade = 0;
            }

            $totalReviews = $resenhaDao->recuperarQtdTotalPorCurso( $curso );

            if ( $totalReviews > 0 ) {
                $listaUltimasReviews = $resenhaDao->recuperarTodosPorCurso(
                    $curso,
                    '',
                    '',
                    5
                );
            } else {
                $listaUltimasReviews = array();
            }

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'totalReviews' => $resenhaDao->recuperarQtdTotalPorCurso( $curso ),
                'mediaQualidade' => round($mediaQualidade, 1),
                'mediaDificuldade' => round($mediaDificuldade, 1),
                'listaUltimasReviews' => $this->template->loadPartial(
                    'lista',
                    array('listaResenhas' => $listaUltimasReviews),
                    'curso/review'
                )
            );

            $this->_renderTemplateCurso($curso, 'curso/review/index', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir index do gerenciamento de reviews'
                . create_exception_description($e));

            show_404();
        }
    }

    public function listar ($idCurso)
    {
        try {
            $count = 30;

            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');

            $curso = $this->_cursoDao->recuperar( $idCurso );

            $totalReviews = $resenhaDao->recuperarQtdTotalPorCurso( $curso );

            if ( $haReviews = $totalReviews > 0 ) {
                $listaReviews = $resenhaDao->recuperarTodosPorCurso(
                    $curso,
                    '',
                    '',
                    $count + 1
                );

                $qtdReviews = count( $listaReviews );
                $qtdReviews = ($qtdReviews > $count) ? $qtdReviews - 1 : $qtdReviews;
            } else {
                $listaReviews = array();
                $qtdReviews = 0;
            }

            $this->load->helper('paginacao_cassandra');

            $paginacao = create_paginacao_cassandra($listaReviews, $count);

            $dadosView = array(
                'idCurso' => $curso->getId(),
                'haReviews' => $haReviews,
                'qtdReviews' => $qtdReviews,
                'totalReviews' => $totalReviews,
                'listaReviews' => $this->template->loadPartial(
                    'lista',
                    array('listaResenhas' => $listaReviews),
                    'curso/review'
                ),
                'haMaisPaginas' => $paginacao['proxima_pagina'],
                'inicioProxPagina' => $paginacao['inicio_proxima_pagina']
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/review/listar',
                $dadosView
            );
        } catch(Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir listagem de reviews de um curso'
                . create_exception_description($e));

            show_404();
        }
    }

    public function recuperar_proxima_pagina($idCurso, $idProximo)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 30;

            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');

            $curso = $this->_cursoDao->recuperar( $idCurso );

            try {
                $listaReviews = $resenhaDao->recuperarTodosPorCurso(
                    $curso,
                    $idProximo,
                    '',
                    $count + 1
                );

                $qtdReviews = count( $listaReviews );
                $qtdReviews = ($qtdReviews > $count) ? $qtdReviews - 1 : $qtdReviews;
            } catch(cassandra_NotFoundException $e) {
                $listaReviews = array();
                $qtdReviews = 0;
            }

            $this->load->helper('paginacao_cassandra');

            $paginacao = create_paginacao_cassandra($listaReviews, $count);

            $response = Zend_Json::encode(array(
                'htmlListaReviews' => $this->template->loadPartial(
                    'lista',
                    array('listaResenhas' => $listaReviews),
                    'curso/review'
                ),
                'qtdReviews' => $qtdReviews,
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);
        } catch(Exception $e) {
            log_message('error','Ocorreu um erro ao tentar recuperar próxima página de reviews de um curso: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function enviar ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');

            $dadosForm = array(
                'formAction' => 'curso/review/salvar',
                'extraOpenForm' => 'id="form-review-criar"',
                'formHidden' => array('acao' => 'criar', 'cursoId' => $curso->getId()),
                'conteudoAtual' => '',
                'qualidadeAtual' => 10,
                'dificuldadeAtual' => 10,
                'txtBotaoSalvar' => 'Enviar Avaliação!'
            );

            $dadosView = array(
                'nomeCurso' => $curso->getNome(),
                'idCurso' => $curso->getId(),
                'usuarioJaEnviou' => $resenhaDao->usuarioJaEnviou(
                    $this->autenticacao->getUsuarioAutenticado(),
                    $curso
                ),
                'form' => $this->template->loadPartial('form', $dadosForm, 'curso/review')
            );

            $this->_renderTemplateCurso($curso, 'curso/review/enviar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir formulário envio de review'
                . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ($idResenha)
    {
        try {
            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');
            $resenha = $resenhaDao->recuperar( $idResenha );

            $dadosForm = array(
                'formAction' => 'curso/review/salvar',
                'extraOpenForm' => 'id="form-review-alterar"',
                'formHidden' => array(
                    'acao' => 'alterar',
                    'resenhaId' => $resenha->getId()
                ),
                'conteudoAtual' => $resenha->getConteudo(),
                'qualidadeAtual' => $resenha->getQualidade(),
                'dificuldadeAtual' => $resenha->getDificuldade(),
                'txtBotaoSalvar' => 'Salvar Avaliação!'
            );

            $dadosView = array(
                'nomeCurso' => $resenha->getCurso()->getNome(),
                'idCurso' => $resenha->getCurso()->getId(),
                'form' => $this->template->loadPartial('form', $dadosForm, 'curso/review')
            );

            $this->_renderTemplateCurso(
                $resenha->getCurso(),
                'curso/review/alterar',
                $dadosView
            );
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir formulário envio de review'
                . create_exception_description($e));

            show_404();
        }
    }

    public function remover ($idResenha)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');

            $resenhaDao->remover( $idResenha );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A avaliação foi removida com sucesso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (Exception $e) {
            log_message('error','Ocorreu um erro ao tentar remover review de curso: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function salvar ()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');

        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            $this->load->helper('notificacao_js');
            try {
                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_adicionar( $this->input->post() );
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'A sua avaliação sobre este curso foi enviada com sucesso!
                            <br> Obrigado pela participação!'
                        );
                        break;
                    case 'alterar':
                        $json = $this->_alterar( $this->input->post() );
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'Os dados da avaliação foram alterados com sucesso!'
                        );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Ação inválida ao salvar!');
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error','Ocorreu um erro ao tentar salvar review de curso: '
                    . create_exception_description($e));

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro inesperado, já estamos tentando resolver.
                    Tente novamente mais tarde!'
                );

                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
    }

    public function responder($idReview)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');
            $resenha = $resenhaDao->recuperar( $idReview );

            $dadosForm = array(
                'formAction' => '/curso/review/salvar_resposta',
                'extraOpenForm' => 'id="form-resposta-review-criar"',
                'formHidden' => array(
                    'acao' => 'criar',
                    'resenhaId' => $resenha->getId()
                ),
                'respostaAtual' => ''
            );

            $dadosView = array(
                'alunoId' => $resenha->getCriador()->getId(),
                'alunoNome' => $resenha->getCriador()->getNome(),
                'form' => $this->template->loadPartial(
                    'form_resposta',
                    $dadosForm,
                    'curso/review'
                )
            );

            $response = Zend_Json::encode(array(
                'htmlFormResponder' => $this->load->view(
                    'curso/review/responder',
                    $dadosView,
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch(Exception $e) {
            log_message('error','Ocorreu um erro ao tentar exibir formulário de resposta à review: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function alterar_resposta($idReview)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');
            $resenha = $resenhaDao->recuperar( $idReview );

            $dadosForm = array(
                'formAction' => '/curso/review/salvar_resposta',
                'extraOpenForm' => 'id="form-resposta-review-alterar"',
                'formHidden' => array(
                    'acao' => 'alterar',
                    'resenhaId' => $resenha->getId()
                ),
                'respostaAtual' => $resenha->getResposta()->getConteudo()
            );

            $dadosView = array(
                'alunoId' => $resenha->getCriador()->getId(),
                'alunoNome' => $resenha->getCriador()->getNome(),
                'form' => $this->template->loadPartial(
                    'form_resposta',
                    $dadosForm,
                    'curso/review'
                )
            );

            $response = Zend_Json::encode(array(
                'htmlFormAlterarResposta' => $this->load->view(
                    'curso/review/alterar_resposta',
                    $dadosView,
                    true
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch(Exception $e) {
            log_message('error','Ocorreu um erro ao tentar exibir formulário de resposta à review: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function remover_resposta($idReview)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');
            $resenha = $resenhaDao->recuperar( $idReview );

            $resenhaDao->removerResposta( $resenha );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A resposta desta avaliação foi removida com sucesso!'
                ),
                'htmlLinkResponder' => anchor(
                    '/curso/review/responder/' . $resenha->getId(),
                    'Responder à Avaliação',
                    'class="a-responder-review"'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch(Exception $e) {
            log_message('error','Ocorreu um erro ao tentar remover resposta de review: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function salvar_resposta()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');

        if ( ! $this->form_validation->run() ) {
            $json = create_json_feedback(false, validation_errors_json());
        } else {
            $this->load->helper('notificacao_js');
            try {
                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_adicionar_resposta( $this->input->post() );
                        break;
                    case 'alterar':
                        $json = $this->_alterar_resposta( $this->input->post() );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Ação inválida ao salvar resposta!');
                }
            } catch (Exception $e) {
                log_message('error','Ocorreu um erro ao tentar salvar resposta à review: '
                    . create_exception_description($e));

                $error = create_json_feedback_error_json(
                    'Ocorreu um erro inesperado, já estamos tentando resolver.
                    Tente novamente mais tarde!'
                );

                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
    }

    private function _adicionar($post)
    {
        $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');

        $curso = $this->_cursoDao->recuperar( $post['cursoId'] );

        $novaResenha = $resenhaDao->criarNovo( $post );
        $novaResenha->setCurso( $curso );
        $novaResenha->setcriador( $this->autenticacao->getUsuarioAutenticado() );

        $resenhaDao->salvar( $novaResenha );

        $response = Zend_Json::encode(array(
            'idCurso' => $curso->getId()
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _alterar($post)
    {
        $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');
        $resenha = $resenhaDao->recuperar( $post['resenhaId'] );

        $resenha->preencherPropriedades( $post );

        $resenhaDao->salvar( $resenha );

        $response = Zend_Json::encode(array(
            'idCurso' => $resenha->getCurso()->getId()
        ));

        return create_json_feedback(true, '', $response);
    }

    private function _adicionar_resposta($post)
    {
        $resenhaDao = WeLearn_DAO_DAOFactory::create('ResenhaDAO');
        $resenha = $resenhaDao->recuperar( $post['resenhaId'] );

        $resposta = new WeLearn_Cursos_Reviews_RespostaResenha( $post );
        $resposta->setCriador( $this->autenticacao->getUsuarioAutenticado() );

        $resenha->setResposta( $resposta );

        $resenhaDao->salvar( $resenha );

        $dadosResposta = array(
            'gerenciadorId' => $resposta->getCriador()->getId(),
            'gerenciadorNome' => $resposta->getCriador()->getNome(),
            'conteudoResposta' => $resposta->getConteudo(),
            'idReview' => $resenha->getId()
        );

        $response = Zend_Json::encode(array(
            'notificacao' => create_notificacao_array(
                'sucesso',
                'A resposta foi salvar com sucesso!'
            ),
            'htmlResposta' => $this->template->loadPartial(
                'resposta',
                $dadosResposta,
                'curso/review'
            )
        ));

        //enviar notificação ao usuário;
        $notificacao = new WeLearn_Notificacoes_NotificacaoResenhaRespondida();
        $notificacao->setResenha( $resenha );
        $notificacao->setDestinatario( $resenha->getCriador() );
        $notificacao->adicionarNotificador( new WeLearn_Notificacoes_NotificadorCassandra() );
        $notificacao->notificar();
        //fim da notificação;

        return create_json_feedback(true, '', $response);
    }

    private function _alterar_resposta($post)
    {
        $respostaDao = WeLearn_DAO_DAOFactory::create('RespostaResenhaDAO');
        $resposta = $respostaDao->recuperar( $post['resenhaId'] );

        $resposta->preencherPropriedades( $post );

        $respostaDao->salvar( $resposta );

        $dadosResposta = array(
            'gerenciadorId' => $resposta->getCriador()->getId(),
            'gerenciadorNome' => $resposta->getCriador()->getNome(),
            'conteudoResposta' => $resposta->getConteudo(),
            'idReview' => $resposta->getResenhaId()
        );

        $response = Zend_Json::encode(array(
            'notificacao' => create_notificacao_array(
                'sucesso',
                'A resposta foi salvar com sucesso!'
            ),
            'htmlResposta' => $this->template->loadPartial(
                'resposta',
                $dadosResposta,
                'curso/review'
            )
        ));

        return create_json_feedback(true, '', $response);
    }
}
