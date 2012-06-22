<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete extends Curso_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('enquete.js');
    }

    public function index ($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar ($idCurso)
    {
        try {
            $count = 10;

            $curso = $this->_cursoDao->recuperar($idCurso);

            $alunoAutorizado = ! ( $curso->getConfiguracao()->getPermissaoCriacaoEnquete() === WeLearn_Cursos_PermissaoCurso::RESTRITO &&
                                   $this->_getNivelAcesso($curso) != WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR &&
                                   $this->_getNivelAcesso($curso) != WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_PRINCIPAL );

            $filtro = $this->input->get('f');

            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');

            try {
                $listaEnquetes = $this->_recuperarEnquetes($curso, $filtro);
            } catch (cassandra_NotFoundException $e) {
                $listaEnquetes = array();
            }

            $this->load->helper('paginacao_cassandra');
            $dadosPaginacao = create_paginacao_cassandra($listaEnquetes, $count);

            $dadosPartialLista = array(
                'papelUsuarioAtual' => $this->_getPapel( $curso ),
                'listaEnquetes' => $listaEnquetes
            );
            $partialLista = $this->template->loadPartial('lista', $dadosPartialLista, 'curso/enquete/enquete');

            $dadosView = array(
                'alunoAutorizado' => $alunoAutorizado,
                'tituloLista' => $this->_tituloLista($filtro),
                'idCurso' => $curso->getId(),
                'qtdTodas' => $enqueteDao->recuperarQtdTotalPorCurso($curso),
                'qtdAtivas' => $enqueteDao->recuperarQtdTotalPorStatus($curso, WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA),
                'qtdInativas' => $enqueteDao->recuperarQtdTotalPorStatus($curso, WeLearn_Cursos_Enquetes_StatusEnquete::INATIVA),
                'qtdAbertas' => $enqueteDao->recuperarQtdTotalPorSituacao($curso, WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA),
                'qtdFechadas' => $enqueteDao->recuperarQtdTotalPorSituacao($curso, WeLearn_Cursos_Enquetes_SituacaoEnquete::FECHADA),
                'haEnquetes' => !empty($listaEnquetes),
                'listaEnquetes' => $partialLista,
                'haMaisPaginas' => $dadosPaginacao['proxima_pagina'],
                'inicioProxPagina' => $dadosPaginacao['inicio_proxima_pagina'],
            );

            $this->_renderTemplateCurso($curso, 'curso/enquete/enquete/listar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir lista de Enquetes: ' . create_exception_description($e));

            show_404();
        }
    }

    public function proxima_pagina($idCurso, $inicio)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        try {
            set_json_header();

            $count = 10;

            $curso = $this->_cursoDao->recuperar($idCurso);

            $filtro = $this->input->get('f');

            try {
                $listaEnquetes = $this->_recuperarEnquetes($curso, $filtro, $inicio);
            } catch (cassandra_NotFoundException $e) {
                $listaEnquetes = array();
            }

            $this->load->helper('paginacao_cassandra');
            $paginacao = create_paginacao_cassandra($listaEnquetes, $count);

            $response = array(
                'success' => true,
                'htmlListaEnquetes' => $this->template->loadPartial(
                    'lista',
                    array(
                        'papelUsuarioAtual' => $this->_getPapel( $curso ),
                        'listaEnquetes' => $listaEnquetes
                    ),
                    'curso/enquete/enquete'
                ),
                'paginacao' => $paginacao
            );

            $json = Zend_Json::encode($response);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar recupera uma nova página de enquetes: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado, já estamos verificando. Tente novamente mais tarde.');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function exibir ($idEnquete)
    {
        try {
            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
            $enquete = $enqueteDao->recuperar($idEnquete);
            $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

            $enqueteFechada = $enquete->getSituacao() == WeLearn_Cursos_Enquetes_SituacaoEnquete::FECHADA;
            if ( $enqueteFechada || $enqueteDao->usuarioJaVotou($usuarioAtual, $enquete) ) {
                $this->session->keep_flashdata('notificacoesFlash');
                redirect('/curso/enquete/exibir_resultados/' . $enquete->getId());
            }

            $enqueteDao->recuperarAlternativas($enquete);

            $dadosView = array(
                'papelUsuarioAtual' => $this->_getPapel( $enquete->getCurso() ),
                'enquete' => $enquete,
                'formAction' => 'enquete/enquete/votar',
                'extraOpenForm' => 'id="form-enquete-votar"',
                'formHidden' => array( 'enqueteId' => $enquete->getId() )
            );

            $this->_renderTemplateCurso($enquete->getCurso(), 'curso/enquete/enquete/exibir', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao exibir a enquete para votação: ' . create_exception_description($e));

            show_404();
        }
    }

    public function exibir_resultados ($idEnquete)
    {
        try {
            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
            $enquete = $enqueteDao->recuperar($idEnquete);

            $enqueteDao->recuperarAlternativas($enquete);
            $enqueteDao->recuperarQtdParcialVotos($enquete);

            $usuarioAtual = $this->autenticacao->getUsuarioAutenticado();

            $dadosView = array(
                'papelUsuarioAtual' => $this->_getPapel( $enquete->getCurso() ),
                'textoSituacao' => ($enquete->getSituacao() ==
                    WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ?
                    'Parciais' : 'Finais',
                'enquete' => $enquete,
                'linkParaVotar' => ! (
                    $enquete->getSituacao() == WeLearn_Cursos_Enquetes_SituacaoEnquete::FECHADA ||
                    $enqueteDao->usuarioJaVotou( $usuarioAtual, $enquete )
                )
            );

            $this->_renderTemplateCurso($enquete->getCurso(), 'curso/enquete/enquete/exibir_resultados', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir resultados de enquete: ' . create_exception_description($e));

            show_404();
        }
    }

    public function criar ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar($idCurso);

            if (
                $curso->getConfiguracao()->getPermissaoCriacaoEnquete() === WeLearn_Cursos_PermissaoCurso::RESTRITO &&
                $this->_getNivelAcesso($curso) != WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR &&
                $this->_getNivelAcesso($curso) != WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_PRINCIPAL
            ) {
                show_404();
            }

            $this->_expulsarNaoAutorizados($curso);

            $dadosPartialForm = array(
                'formAction' => 'enquete/enquete/salvar',
                'extraOpenForm' => 'id="form-criar-enquete"',
                'hiddenFormData' => array('cursoId' => $curso->getId(), 'acao' => 'criar'),
                'questaoAtual' => '',
                'enquete' => false,
                'i' => 0,
                'dataExpiracaoAtual' => '',
                'txtBotaoEnviar' => 'Enviar e publicar!'
            );

            $dadosViewCriar = array(
                'formCriar' => $this->template->loadPartial('form', $dadosPartialForm, 'curso/enquete/enquete')
            );

            $this->_renderTemplateCurso($curso, 'curso/enquete/enquete/criar', $dadosViewCriar);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de criação de enquetes:'
                . create_exception_description($e));

            show_404();
        }
    }

    public function alterar ($idEnquete)
    {
        try {
            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
            $enquete = $enqueteDao->recuperar($idEnquete);
            $enqueteDao->recuperarAlternativas($enquete);

            $dadosPartialForm = array(
                'formAction' => 'enquete/enquete/salvar',
                'extraOpenForm' => 'id="form-alterar-enquete"',
                'hiddenFormData' => array('enqueteId' => $enquete->getId(), 'acao' => 'alterar'),
                'questaoAtual' => $enquete->getQuestao(),
                'enquete' => $enquete,
                'i' => 0,
                'dataExpiracaoAtual' => date('d/m/Y', $enquete->getDataExpiracao()),
                'txtBotaoEnviar' => 'Salvar!'
            );

            $dadosView = array(
                'formAlterar' => $this->template->loadPartial('form', $dadosPartialForm, 'curso/enquete/enquete')
            );

            $this->_renderTemplateCurso($enquete->getCurso(), 'curso/enquete/enquete/alterar', $dadosView);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar exibir formulário de alteração: ' . create_exception_description($e));

            show_404();
        }
    }

    public function votar ()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');

        if ( ! $this->form_validation->run() ) {

            $json = create_json_feedback(false, validation_errors_json());

        } else {
            try {
                $this->load->helper('notificacao_js');

                $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
                $alternativaDao = $enqueteDao->getAlternativaEnqueteDAO();

                $enquete = $enqueteDao->recuperar( $this->input->post('enqueteId') );
                $usuarioVotante = $this->autenticacao->getUsuarioAutenticado();
                $alternativaVotada = $alternativaDao->recuperar( $this->input->post('alternativaEscolhida') );

                $voto = new WeLearn_Cursos_Enquetes_VotoEnquete(0, $enquete, $usuarioVotante, $alternativaVotada);

                $enqueteDao->votar($voto);

                $dadosResponse = Zend_Json::encode(array(
                    'idEnquete' => $enquete->getId()
                ));

                $json = create_json_feedback(true, '', $dadosResponse);

                $textoSituacao = ($enquete->getSituacao() == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ? 'parcial' : 'final';

                $notificacoesFlash = create_notificacao_json(
                    'sucesso',
                    'Seu voto foi registrado com sucesso! <br> Veja abaixo o resultado <strong>' . $textoSituacao . '</strong> da enquete.',
                    10000
                );

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch (Exception $e) {
                log_message('error', 'Erro ao registrar voto em enquete: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ocorreu um erro inesperado, já estamos tentando resolver. Tente novamente mais tarde!');

                $json = create_json_feedback(false, $error);
            }
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
            try {
                $this->load->helper('notificacao_js');

                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criarEnquete( $this->input->post() );
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'A nova enquete foi criada com sucesso, na verdade você
                             já está nela!<br/>Caso pense em alguma alteração faça-a
                             o mais rápido possível!'
                        );
                        break;
                    case 'alterar';
                        $json = $this->_alterarEnquete( $this->input->post() );
                        $notificacoesFlash = create_notificacao_json(
                            'sucesso',
                            'As alterações na enquete foram salvas com sucesso!'
                        );
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Opção de salvamento inválida!');
                }

                $this->session->set_flashdata('notificacoesFlash', $notificacoesFlash);
            } catch ( Exception $e ) {
                log_message('error', 'Erro ao tentar salvar enquete: ' . create_exception_description($e));

                $error = create_json_feedback_error_json('Ocorreu um erro inesperado, já estamos tentando resolver. Tente novamente mais tarde!');

                $json = create_json_feedback(false, $error);
            }
        }

        echo $json;
    }

    public function remover ($idEnquete)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');

            $enqueteRemovida = $enqueteDao->remover($idEnquete);

            $this->load->helper('notificacao_js');

            $notificacao = Zend_Json::encode(array(
                'idCurso' => $enqueteRemovida->getCurso()->getId(),
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A enquete <strong>' . $enqueteRemovida->getQuestao() . '</strong> foi removida com sucesso!',
                    10000
                )
            ));

            $json = create_json_feedback(true, '', $notificacao);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar remover enquete de curso: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado, já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function alterar_status ($idEnquete)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');

            $enquete = $enqueteDao->recuperar($idEnquete);
            $enquete->alterarStatus();

            $enqueteDao->salvar($enquete);

            $strStatus = ($enquete->getStatus() == WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA) ? 'ativada' : 'desativada';

            $this->load->helper('notificacao_js');

            $notificacao = Zend_Json::encode(array(
                'statusAtual' => $strStatus,
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A enquete <strong>' . $enquete->getQuestao() . '</strong> foi ' . $strStatus . ' com sucesso!',
                    10000
                )
            ));

            $json = create_json_feedback(true, '', $notificacao);
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar alterar status da enquete de curso: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado, já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function alterar_situacao ($idEnquete)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');

            $enquete = $enqueteDao->recuperar($idEnquete);
            $enquete->alterarSituacao();

            $enqueteDao->salvar($enquete);

            $strSituacao = ($enquete->getSituacao() == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ? 'reaberta' : 'fechada';

            $this->load->helper('notificacao_js');

            $notificacao_array = create_notificacao_array(
                'sucesso',
                'A enquete <strong>' . $enquete->getQuestao() . '</strong> foi ' . $strSituacao . ' com sucesso!',
                10000
            );

            $notificacao = Zend_Json::encode(array(
                'notificacao' => $notificacao_array
            ));

            $json = create_json_feedback(true, '', $notificacao);

            if ( $this->input->get('exibindoEnquete') ) {
                $this->session->set_flashdata('notificacoesFlash',
                    Zend_Json::encode($notificacao_array));
            }
        } catch (Exception $e) {
            log_message('error', 'Erro ao tentar alterar situação da enquete de curso: ' . create_exception_description($e));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado, já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    private function _criarEnquete ($post)
    {
        $curso = $this->_cursoDao->recuperar( $post['cursoId'] );

        $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
        $novaEnquete = $enqueteDao->criarNovo();

        $this->load->helper('date');
        $dataExpiracao = datetime_ptbr_to_en($post['dataExpiracao'], true);

        $novaEnquete->setCurso( $curso );
        $novaEnquete->setCriador( $this->autenticacao->getUsuarioAutenticado() );
        $novaEnquete->setQuestao( $post['questao'] );
        $novaEnquete->setDataExpiracao( $dataExpiracao );
        $novaEnquete->setQtdAlternativas( count( $post['alternativas'] ) );

        $enqueteDao->salvar( $novaEnquete );

        foreach ($post['alternativas'] as $alternativa ) {
            $novaAlternativa = $enqueteDao->criarAlternativa(array(
                'txtAlternativa' => $alternativa,
                'enqueteId' => $novaEnquete->getId()
            ));

            $novaEnquete->adicionarAlternativa( $novaAlternativa );
        }

        $enqueteDao->salvarAlternativas( $novaEnquete->getAlternativas() );

        return create_json_feedback(true, '', Zend_Json::encode( array('idEnquete' => $novaEnquete->getId()) ));
    }

    private function _alterarEnquete ($post)
    {
        $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
        $enquete = $enqueteDao->recuperar( $post['enqueteId'] );

        $this->load->helper('date');
        $dataExpiração = datetime_ptbr_to_en($post['dataExpiracao'], true);

        $enquete->setQuestao( $post['questao'] );
        $enquete->setDataExpiracao( $dataExpiração );
        $enquete->setQtdAlternativas( count( $post['alternativas'] ) );

        $enqueteDao->salvar( $enquete );

        $enqueteDao->zerarVotos( $enquete );

        $enqueteDao->recuperarAlternativas( $enquete );

        $enqueteDao->removerAlternativas( $enquete->getAlternativas() );

        $enquete->zerarAlternativas();

        foreach ($post['alternativas'] as $alternativa ) {
            $novaAlternativa = $enqueteDao->criarAlternativa(array(
                'txtAlternativa' => $alternativa,
                'enqueteId' => $enquete->getId()
            ));

            $enquete->adicionarAlternativa( $novaAlternativa );
        }

        $enqueteDao->salvarAlternativas( $enquete->getAlternativas() );

        return create_json_feedback(true, '', Zend_Json::encode( array('idEnquete' => $enquete->getId()) ));
    }

    private function _recuperarEnquetes(WeLearn_Cursos_Curso $curso, $filtro, $inicio = '', $count = 10)
    {
        $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');

        switch ($filtro) {
            case 'todas':
                return $enqueteDao->recuperarTodosPorCurso( $curso, $inicio, '', $count + 1 );
            case 'ativas':
                return $enqueteDao->recuperarTodosPorStatus(
                    $curso,
                    WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA,
                    $inicio,
                    '',
                    $count + 1
                );
            case 'inativas':
                return $enqueteDao->recuperarTodosPorStatus(
                    $curso,
                    WeLearn_Cursos_Enquetes_StatusEnquete::INATIVA,
                    $inicio,
                    '',
                    $count + 1
                );
            case 'fechadas':
                return $enqueteDao->recuperarTodosPorSituacao(
                    $curso,
                    WeLearn_Cursos_Enquetes_SituacaoEnquete::FECHADA,
                    $inicio,
                    '',
                    $count + 1
                );
            case 'abertas':
            default:
                return $enqueteDao->recuperarTodosPorSituacao(
                    $curso,
                    WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA,
                    $inicio,
                    '',
                    $count + 1
                );
        }
    }

    private function _tituloLista($filtro)
    {
        switch ($filtro) {
            case 'todas': return '- Todas as Enquetes' ;
            case 'ativas': return ' - Enquetes Ativas' ;
            case 'inativas': return ' - Enquetes Inativas' ;
            case 'fechadas': return ' - Enquetes Fechadas' ;
            case 'abertas': return ' - Enquetes Abertas';
            default: return '' ;
        }
    }

    public function _validarQtdAlternativas($alternativas)
    {
        $this->form_validation->set_message(
            '_validarQtdAlternativas',
            'O número de %s contidas nesta enquete é inválido.'
        );

        return (count($alternativas) >= 2) && (count($alternativas) <= 10);
    }
}
