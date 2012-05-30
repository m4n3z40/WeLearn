<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 28/05/12
 * Time: 12:00
 * To change this template use File | Settings | File Templates.
 */
class Exibicao extends Curso_Controller
{
    /**
     * @var AlunoDAO
     */
    private $_alunoDao;

    /**
     * @var ParticipacaoCursoDAO
     */
    private $_participacaoCursoDao;

    /**
     * @var PaginaDAO
     */
    private $_paginaDao;

    /**
     * @var AulaDAO
     */
    private $_aulaDao;

    /**
     * @var ModuloDAO
     */
    private $_moduloDao;

    /**
     * @var AvaliacaoDAO
     */
    private $_avaliacaoDao;

    /**
     * @var QuestaoAvaliacaoDAO
     */
    private $_questaoAvaliacaoDao;

    /**
     * @var AlternativaAvaliacaoDAO
     */
    private $_alternativaAvaliacaoDao;

    /**
     * @var ControleAvaliacaoDAO
     */
    private $_controleAvaliacaoDao;

    /**
     * @var AnotacaoDAO
     */
    private $_anotacaoDao;

    /**
     * @var ComentarioDAO
     */
    private $_comentarioDao;

    /**
     * @var RecursoDAO
     */
    private $_recursoDao;

    /**
     * @var WeLearn_Usuarios_Aluno
     */
    private $_alunoAtual;

    public function __construct()
    {
        parent::__construct();

        $this->_alunoDao = WeLearn_DAO_DAOFactory::create('AlunoDAO');
        $this->_participacaoCursoDao = WeLearn_DAO_DAOFactory::create('ParticipacaoCursoDAO');
        $this->_paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
        $this->_aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
        $this->_moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');

        $this->_alunoAtual = $this->_alunoDao->criarAluno(
            $this->autenticacao->getUsuarioAutenticado()
        );

        $this->template->appendJSImport('exibicao_conteudo_curso.js');
    }

    public function index( $idCurso )
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $participacaoCurso = $this->_participacaoCursoDao->recuperarPorCurso(
                $this->_alunoAtual,
                $curso
            );

            $totalPaginas = $this->_paginaDao->recuperarQtdTotalPorCurso( $curso );

            if ( $participacaoCurso->getPaginaAtual() instanceof WeLearn_Cursos_Conteudo_Pagina ) {

                $iniciouCurso = true;

                $paginaAtual = $participacaoCurso->getPaginaAtual();
                $aulaAtual = $paginaAtual->getAula();
                $moduloAtual = $aulaAtual->getModulo();

                $totalPaginasVistas = $this->_participacaoCursoDao
                                           ->recuperarQtdTotalControlesPagina(
                                               $participacaoCurso
                                           );

            } else {

                $iniciouCurso = false;
                $totalPaginasVistas = 0;

                try {
                    $moduloAtual = $this->_moduloDao->recuperarTodosPorCurso(
                        $curso,
                        '',
                        '',
                        1
                    );

                    $moduloAtual = $moduloAtual[0];
                } catch (Exception $e) {
                    $moduloAtual = null;
                }

                try {
                    $aulaAtual = $this->_aulaDao->recuperarTodosPorModulo(
                        $moduloAtual,
                        '',
                        '',
                        1
                    );

                    $aulaAtual = $aulaAtual[0];
                } catch (Exception $e) {
                    $aulaAtual = null;
                }

                try {
                    $paginaAtual = $this->_paginaDao->recuperarTodosPorAula(
                        $aulaAtual,
                        '',
                        '',
                        1
                    );

                    $paginaAtual = $paginaAtual[0];
                } catch (Exception $e) {
                    $paginaAtual = null;
                }
            }

            $dadosViewSalaDeAula = array(
                'idCurso' => $curso->getId(),
                'idModulo' => ( $moduloAtual instanceof WeLearn_Cursos_Conteudo_Modulo )
                              ? $moduloAtual->getId() : '',
                'idAula' => ( $aulaAtual instanceof WeLearn_Cursos_Conteudo_Aula )
                            ? $aulaAtual->getId() : '',
                'idPagina' => ($paginaAtual instanceof WeLearn_Cursos_Conteudo_Pagina)
                              ? $paginaAtual->getId() : '',
                'htmlSectionAnotacao' => $this->_loadSectionAnotacaoView( $paginaAtual ),
                'htmlSectionComentarios' => $this->_loadSectionComentariosView( $paginaAtual ),
                'htmlSectionInfoEtapa' => $this->_loadSectionInfoEtapaView( $paginaAtual ),
                'htmlSectionRecursos' => $this->_loadSectionRecursosView( $aulaAtual )
            );

            $dadosView = array(
                'iniciouCurso' => $iniciouCurso,
                'paginaAtual' => $paginaAtual,
                'aulaAtual' => $aulaAtual,
                'moduloAtual' => $moduloAtual,
                'progressoNoCurso' => number_format(
                    ( $totalPaginasVistas / $totalPaginas) * 100,
                    2
                ),
                'htmlJanelaSalaDeAula' => $this->load->view(
                    'curso/conteudo/exibicao/sala_de_aula',
                    $dadosViewSalaDeAula,
                    true
                )
            );

            $this->_renderTemplateCurso(
                $curso,
                'curso/conteudo/exibicao/index',
                $dadosView
            );

        } catch(Exception $e) {
            log_message('error', 'Erro ao tentar index do modulo de visualização de conteudo do curso.');

            show_404();
        }
    }

    public function exibir()
    {
        echo 'Pagina';
    }

    public function salvar_anotacao($idPagina)
    {
        if ( ! $this->input->is_ajax_request() ) {
            show_404();
        }

        set_json_header();

        try {

            $this->_anotacaoDao = WeLearn_DAO_DAOFactory::create('AnotacaoDAO');

            $pagina = $this->_paginaDao->recuperar( $idPagina );

            $anotacao = $this->_anotacaoDao->criarNovo(array(
                'conteudo' => $this->input->post('anotacao'),
                'usuario' => $this->_alunoAtual,
                'pagina' => $pagina
            ));

            $this->_anotacaoDao->salvar( $anotacao );

            $json = create_json_feedback( true );

        } catch( Exception $e ) {
            log_message('error', 'Ocorreu um erro ao tentar salvar anotação de página: '
                . create_exception_description( $e ));

            $error = create_json_feedback_error_json('Ocorreu um erro inesperado,
                        já estamos tentando resolver. Tente novamente mais tarde!');

            $json = create_json_feedback(false, $error);
        }

        echo $json;
    }

    private function _loadSectionAnotacaoView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $this->_anotacaoDao = WeLearn_DAO_DAOFactory::create('AnotacaoDAO');

        try {
            $anotacaoAtual = $this->_anotacaoDao->recuperarPorUsuario(
                $pagina,
                $this->_alunoAtual
            );
        } catch (cassandra_NotFoundException $e) {
            $anotacaoAtual = null;
        }

        $dadosAnotacaoView = array(
            'formAction' => '/curso/conteudo/exibicao/salvar_anotacao/' . $pagina->getId(),
            'extraOpenForm' => 'id="exibicao-conteudo-anotacao-form"',
            'formHidden' => array(),
            'idPagina' => $pagina->getId(),
            'anotacaoAtual' => $anotacaoAtual
        );

        return $this->template->loadPartial(
            'section_anotacao',
            $dadosAnotacaoView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadSectionComentariosView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $dadosFormComentario = array(
            'formAction' => 'conteudo/comentario/salvar',
            'extraOpenForm' => 'id="form-comentario-criar"',
            'formHidden' => array('acao' => 'criar', 'paginaId' => $pagina->getId()),
            'assuntoAtual' => '',
            'txtComentarioAtual' => '',
            'idBotaoEnviar' => 'btn-form-comentario-criar',
            'txtBotaoEnviar' => 'Postar Comentário!'
        );

        $dadosComentariosView = array(
            'formCriar' => $this->template->loadPartial(
                'form',
                $dadosFormComentario,
                'curso/conteudo/comentario'
            )
        );

        return $this->template->loadPartial(
            'section_comentarios',
            $dadosComentariosView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadSectionInfoEtapaView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $this->load->helper(array('modulo', 'aula', 'pagina'));

        $listaModulos = $this->_moduloDao->recuperarTodosPorCurso(
            $pagina->getAula()->getModulo()->getCurso()
        );

        $listaAulas = $this->_aulaDao->recuperarTodosPorModulo(
            $pagina->getAula()->getModulo()
        );

        $listaPaginas = $this->_paginaDao->recuperarTodosPorAula(
            $pagina->getAula()
        );

        $dadosInfoEtapaView = array(
            'modulo' => $pagina->getAula()->getModulo(),
            'aula' => $pagina->getAula(),
            'pagina' => $pagina,
            'selectModulos' => $this->template->loadPartial(
                'select_modulos',
                array(
                    'listaModulos' => lista_modulos_para_dados_dropdown( $listaModulos ),
                    'moduloSelecionado' => $pagina->getAula()->getModulo()->getId(),
                    'extra' => 'id="slt-modulos"'
                ),
                'curso/conteudo'
            ),
            'selectAulas' => $this->template->loadPartial(
                'select_aulas',
                array(
                    'listaAulas' => lista_aulas_para_dados_dropdown( $listaAulas ),
                    'aulaSelecionada' => $pagina->getAula()->getId(),
                    'extra' => 'id="slt-aulas"'
                ),
                'curso/conteudo'
            ),
            'selectPaginas' => $this->template->loadPartial(
                'select_paginas',
                array(
                    'listaPaginas' => lista_paginas_para_dados_dropdown( $listaPaginas ),
                    'paginaSelecionada' => $pagina->getId(),
                    'extra' => 'id="slt-paginas"'
                ),
                'curso/conteudo'
            )
        );

        return $this->template->loadPartial(
            'section_info_etapa',
            $dadosInfoEtapaView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadSectionRecursosView(WeLearn_Cursos_Conteudo_Aula $aula = null)
    {
        $dadosRecursosView = array();

        return $this->template->loadPartial(
            'section_recursos',
            $dadosRecursosView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadConteudoPaginaView(WeLearn_Cursos_Conteudo_Pagina $pagina = null)
    {
        $dadosConteudoPaginaView = array();

        return $this->template->loadPartial(
            'conteudo_pagina',
            $dadosConteudoPaginaView,
            'curso/conteudo/exibicao'
        );
    }

    private function _loadAplicacaoAvaliacaoView(WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao = null)
    {
        $dadosAplicacaoAvaliacaoView = array();

        return $this->template->loadPartial(
            'aplicacao_avaliacao',
            $dadosAplicacaoAvaliacaoView,
            'curso/conteudo/exibicao'
        );
    }
}
