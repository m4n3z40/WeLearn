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

            $dadosView = array(
                'iniciouCurso' => $iniciouCurso,
                'paginaAtual' => $paginaAtual,
                'aulaAtual' => $aulaAtual,
                'moduloAtual' => $moduloAtual,
                'progressoNoCurso' => number_format(
                    ( $totalPaginasVistas / $totalPaginas) * 100,
                    2
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
}
