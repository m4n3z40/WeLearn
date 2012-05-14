<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Aluno extends Curso_Controller
{
    /**
     * @var AlunoDAO
     */
    private $_alunoDao;

    public function __construct()
    {
        parent::__construct();

        $this->template->appendJSImport('aluno.js');

        $this->_alunoDao = WeLearn_DAO_DAOFactory::create('AlunoDAO');
    }

    public function index ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            try {
                $ultimasRequisicoes = $this->_alunoDao->recuperarTodasInscricoesPorCurso( $curso, '', '', 5 );
            } catch (cassandra_NotFoundException $e) {
                $ultimasRequisicoes = array();
            }

            $dadosView = array(
                'haInscricoes' => count( $ultimasRequisicoes ) > 0,
                'idCurso' => $curso->getId(),
                'ultimasRequisicoes' => $this->template->loadPartial(
                    'lista_requisicoes',
                    array(
                        'listaRequisicoes' => $ultimasRequisicoes,
                        'idCurso' => $curso->getId()
                    ),
                    'curso/aluno'
                )
            );

            $this->_renderTemplateCurso($curso, 'curso/aluno/index', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir index do gerenciamento de alunos'
                . create_exception_description($e));

            show_404();
        }
    }

    public function listar ($idCurso)
    {
        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar( $idCurso );

            $dadosView = array(

            );

            $this->_renderTemplateCurso($curso, 'curso/aluno/listar', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir lista de alunos do curso: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function requisicoes ($idCurso)
    {
        try {
            $count = 20;

            $curso = $this->_cursoDao->recuperar( $idCurso );

            try {
                $listaRequisicoes = $this->_alunoDao->recuperarTodasInscricoesPorCurso( $curso, '', '', $count + 1 );
                $totalRequisicoes = $this->_alunoDao->recuperarQtdTotalInscricoesPorCurso( $curso );
            } catch (cassandra_NotFoundException $e) {
                $listaRequisicoes = array();
                $totalRequisicoes = 0;
            }

            $this->load->helper( 'paginacao_cassandra' );
            $paginacao = create_paginacao_cassandra( $listaRequisicoes, $count );

            $dadosView = array(
                'haRequisicoes' => $totalRequisicoes > 0,
                'qtdRequisicoes' => count( $listaRequisicoes ),
                'totalRequisicoes' => $totalRequisicoes,
                'listaRequisicoes' => $this->template->loadPartial(
                    'lista_requisicoes',
                    array(
                        'listaRequisicoes' => $listaRequisicoes,
                        'idCurso' => $curso->getId()
                    ),
                    'curso/aluno'
                ),
                'haMaisPaginas' => $paginacao['proxima_pagina'],
                'idProximo' => $paginacao['inicio_proxima_pagina'],
                'idCurso' => $curso->getId()
            );

            $this->_renderTemplateCurso($curso, 'curso/aluno/requisicoes', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir lista de requisições de inscrição: '
                . create_exception_description($e));

            show_404();
        }
    }

    public function mais_requisicoes ( $idCurso )
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $count = 20;

            $idProximo = $this->input->get( 'proximo' );

            $curso = $this->_cursoDao->recuperar( $idCurso );

            try {
                $listaRequisicoes = $this->_alunoDao->recuperarTodasInscricoesPorCurso( $curso, $idProximo, '', $count + 1 );
            } catch (cassandra_NotFoundException $e) {
                $listaRequisicoes = array();
            }

            $this->load->helper( 'paginacao_cassandra' );
            $paginacao = create_paginacao_cassandra( $listaRequisicoes, $count );

            $response = Zend_Json::encode(array(
                'htmlListaRequisicoes' => $this->template->loadPartial(
                    'lista_requisicoes',
                    array(
                        'listaRequisicoes' => $listaRequisicoes,
                        'idCurso' => $curso->getId()
                    ),
                    'curso/aluno'
                ),
                'qtdRequisicoes' => count( $listaRequisicoes ),
                'paginacao' => $paginacao
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar recuparar proxima página da lista de requisições de inscricao: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function aceitar_requisicao ($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $idUsuario = $this->input->get( 'id-usuario' );

            $curso = $this->_cursoDao->recuperar( $idCurso );

            $usuario = $this->_alunoDao->recuperar( $idUsuario );

            $this->_alunoDao->aceitarRequisicaoInscricao( $usuario, $curso );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A inscrição foi aceita com sucesso!
                    <br> O novo aluno foi notificado e já poderá acessar o conteúdo do curso!'
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar aceitar requisição de inscricao: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    public function recusar_requisicao ($idCurso)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {
            $idUsuario = $this->input->get( 'id-usuario' );

            $curso = $this->_cursoDao->recuperar( $idCurso );

            $usuario = $this->_alunoDao->recuperar( $idUsuario );

            $this->_alunoDao->recusarRequisicaoInscricao( $usuario, $curso );

            $this->load->helper('notificacao_js');

            $response = Zend_Json::encode(array(
                'notificacao' => create_notificacao_array(
                    'sucesso',
                    'A inscrição do aluno foi recusada com sucesso!<br>O usuário será notificado desta má notícia :('
                )
            ));

            $json = create_json_feedback(true, '', $response);
        } catch (cassandra_NotFoundException $e) {
            log_message('error', 'Erro ao tentar recusar requisição de inscricao: '
                . create_exception_description($e));

            $error = create_json_feedback_error_json(
                'Ocorreu um erro inesperado, já estamos tentando resolver.
                Tente novamente mais tarde!'
            );

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    protected function _renderTemplateCurso(WeLearn_Cursos_Curso $curso,
                                            $view = '',
                                            array $dados = null)
    {
        $this->_barraDireitaSetVar(
            'menuContexto',
            $this->template->loadPartial(
                'menu',
                array(
                    'idCurso' => $curso->getId(),
                    'totalAlunos' => $this->_alunoDao->recuperarQtdTotalPorCurso( $curso ),
                    'totalRequisicoes' => $this->_alunoDao->recuperarQtdTotalInscricoesPorCurso( $curso )
                ),
                'curso/aluno'
            )
        );

        parent::_renderTemplateCurso($curso, $view, $dados);
    }
}
