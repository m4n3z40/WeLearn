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

            } catch (cassandra_NotFoundException $e) {
                
            }

            $dadosView = array(

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
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $dadosView = array(

            );

            $this->_renderTemplateCurso($curso, 'curso/aluno/listar', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir index do gerenciamento de alunos'
                . create_exception_description($e));

            show_404();
        }
    }

    public function requisicoes ($idCurso)
    {
        try {
            $curso = $this->_cursoDao->recuperar( $idCurso );

            $dadosView = array(

            );

            $this->_renderTemplateCurso($curso, 'curso/aluno/requisicoes', $dadosView );
        } catch (Exception $e) {
            log_message('error', 'Ocorreu um erro ao tentar exibir index do gerenciamento de alunos'
                . create_exception_description($e));

            show_404();
        }
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
