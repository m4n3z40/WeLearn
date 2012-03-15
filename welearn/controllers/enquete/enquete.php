<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enquete extends WL_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->template->setTemplate('curso')
                       ->appendJSImport('enquete.js');
    }

    public function index ($idCurso)
    {
        $this->listar($idCurso);
    }

    public function listar ($idCurso)
    {
        try {
            $count = 10;

            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

            $filtro = $this->input->get('f');

            try {
                $listaEnquetes = $this->_recuperarEnquetes($curso, $filtro);
            } catch (cassandra_NotFoundException $e) {
                $listaEnquetes = array();
            }

            $this->load->helper('paginacao_cassandra');
            $dadosPaginacao = create_paginacao_cassandra($listaEnquetes, $count);

            $dadosPartialLista = array( 'listaEnquetes' => $listaEnquetes );
            $partialLista = $this->template->loadPartial('lista', $dadosPartialLista, 'curso/enquete/enquete');

            $dadosView = array(
                'idCurso' => $curso->getId(),
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

            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

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
                'htmlListaEnquetes' => $this->template->loadPartial('lista', array('listaEnquetes' => $listaEnquetes), 'curso/enquete/enquete'),
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
            $enquete->recuperarAlternativas();

            $dadosView = array(
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

    public function criar ($idCurso)
    {
        try {
            $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
            $curso = $cursoDao->recuperar($idCurso);

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
            $enquete->recuperarAlternativas();

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
            echo create_exception_description($e);
        }
    }

    public function votar ()
    {
        print_r($_POST);
    }

    public function salvar ()
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        $this->load->library('form_validation');

        $this->form_validation->set_message('_validarQtdAlternativas', 'O número de %s contidas nesta enquete é inválido.');

        if ( ! $this->form_validation->run() ) {

            $json = create_json_feedback(false, validation_errors_json());

        } else {
            try {

                switch ( $this->input->post('acao') ) {
                    case 'criar':
                        $json = $this->_criarEnquete( $this->input->post() );
                        break;
                    case 'alterar';
                        break;
                    default:
                        throw new WeLearn_Base_Exception('Opção de salvamento inválida!');
                }

            } catch ( Exception $e ) {

                echo create_exception_description($e);

            }
        }

        echo $json;
    }

    public function remover ($idEnquete)
    {

    }

    private function _criarEnquete ($post)
    {
        $cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $curso = $cursoDao->recuperar( $post['cursoId'] );

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

    private function _renderTemplateCurso(WeLearn_Cursos_Curso $curso = null, $view = '', array $dados = null)
    {
        $dadosBarraEsquerda = array(
            'idCurso' => $curso->getId()
        );

        $dadosBarraDireita = array(
            'nome' => $curso->getNome(),
            'imagemUrl' => ($curso->getImagem() instanceof WeLearn_Cursos_ImagemCurso)
                          ? $curso->getImagem()->getUrl()
                          : site_url($this->config->item('default_curso_img_uri')),
            'descricao' => $curso->getDescricao(),
            'menuContexto' => ''
        );

        $this->template->setDefaultPartialVar('curso/barra_lateral_esquerda', $dadosBarraEsquerda)
                       ->setDefaultPartialVar('curso/barra_lateral_direita', $dadosBarraDireita)
                       ->render($view, $dados);
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

    public function _validarQtdAlternativas($alternativas)
    {
        return (count($alternativas) >= 2) && (count($alternativas) <= 10);
    }
}
