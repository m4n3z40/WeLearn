<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Suporte Técnico
 * Date: 11/08/11
 * Time: 08:36
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . '/UsuarioDAO.php';

class AlunoDAO extends UsuarioDAO
{

    private $_nomeAlunosPorCursoCF = 'cursos_aluno_por_curso';
    private $_nomeIncricoesPorCursoCF = 'cursos_inscricao_por_curso';

    /**
     * @var ColumnFamily|null
     */
    private $_alunosPorCursoCF;

    /**
     * @var ColumnFamily|null
     */
    private $_inscricoesPorCursoCF;

    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    /**
     * @var ParticipacaoCursoDAO
     */
    private $_participacaoCursoDAO;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $phpCassa = WL_Phpcassa::getInstance();

        $this->_alunosPorCursoCF = $phpCassa->getColumnFamily(
            $this->_nomeAlunosPorCursoCF
        );

        $this->_inscricoesPorCursoCF = $phpCassa->getColumnFamily(
            $this->_nomeIncricoesPorCursoCF
        );

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $this->_participacaoCursoDAO = WeLearn_DAO_DAOFactory::create('ParticipacaoCursoDAO');
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     * @return WeLearn_Usuarios_Aluno
     */
    public function inscrever(WeLearn_Usuarios_Usuario $usuario,
                              WeLearn_Cursos_Curso $noCurso)
    {
        $aluno = $this->criarAluno( $usuario );

        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_alunosPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $aluno->getId() => '' )
        );

        $this->_cursoDao->salvarAluno( $aluno, $noCurso );
        $this->_participacaoCursoDAO->inscrever( $aluno, $noCurso );

        return $aluno;
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $noCurso
     */
    public function desvincular(WeLearn_Usuarios_Aluno $aluno,
                                WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_alunosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $aluno->getId() )
        );

        $this->_cursoDao->removerAluno( $aluno, $noCurso );
        $this->_participacaoCursoDAO->desvincular( $aluno, $noCurso );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     */
    public function enviarRequisicaoInscricao(WeLearn_Usuarios_Usuario $usuario,
                                              WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_inscricoesPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $usuario->getId() => '' )
        );

        $this->_cursoDao->salvarInscricao( $usuario, $noCurso );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     * @return WeLearn_Usuarios_Aluno
     */
    public function aceitarRequisicaoInscricao(WeLearn_Usuarios_Usuario $usuario,
                                               WeLearn_Cursos_Curso $noCurso)
    {
        $this->recusarRequisicaoInscricao( $usuario, $noCurso );

        return $this->inscrever( $usuario, $noCurso );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     */
    public function recusarRequisicaoInscricao(WeLearn_Usuarios_Usuario $usuario,
                                               WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_inscricoesPorCursoCF->remove( $cursoUUID->bytes, array( $usuario->getId() ) );

        $this->_cursoDao->removerInscricao( $usuario, $noCurso );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorCurso(WeLearn_Cursos_Curso $curso,
                                           $de = '',
                                           $ate = '',
                                           $count = 20)
    {
        $ids = $this->recuperarTodasIdsPorCurso($curso, $de, $ate, $count);

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosAlunosFromCassandra( $columns );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodasIdsPorCurso(WeLearn_Cursos_Curso $curso,
                                              $de = '',
                                              $ate = '',
                                              $count = 20)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        return array_keys(
            $this->_alunosPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                $de,
                $ate,
                false,
                $count
            )
        );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodasInscricoesPorCurso(WeLearn_Cursos_Curso $curso,
                                                     $de = '',
                                                     $ate = '',
                                                     $count = 20)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        $ids = array_keys(
            $this->_inscricoesPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                $de,
                $ate,
                false,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        return $this->_alunosPorCursoCF->get_count( $cursoUUID->bytes );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalInscricoesPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        return $this->_inscricoesPorCursoCF->get_count( $cursoUUID->bytes );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     */
    private function removerTodosPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::mint( $curso->getId() );

        $idAlunos = array_keys(
            $this->_alunosPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                '',
                '',
                false,
                1000000
            )
        );

        $idsInscricoes = array_keys(
            $this->_inscricoesPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                '',
                '',
                false,
                1000000
            )
        );

        $this->_alunosPorCursoCF->remove( $cursoUUID->bytes );
        $this->_inscricoesPorCursoCF->remove( $cursoUUID->bytes );

        $this->_cursoDao->removerTodosAlunos( $idAlunos, $curso );
        $this->_cursoDao->removerTodasInscricoes( $idsInscricoes, $curso );
    }

    /**
     * @param array $column
     * @return WeLearn_Usuarios_Aluno
     */
    private function _criarAlunoFromCassandra(array $column)
    {
        $column['segmentoInteresse'] = $this->_segmentoDao->recuperar(
            $column['segmentoInteresse']
        );

        $aluno = $this->criarAluno();

        $aluno->fromCassandra( $column );

        return $aluno;
    }

    /**
     * @param array $columns
     * @return array
     */
    private function _criarVariosAlunosFromCassandra(array $columns)
    {
        $listaAlunos = array();

        foreach ($columns as $column) {
            $listaAlunos[] = $this->_criarAlunoFromCassandra( $column );
        }

        return $listaAlunos;
    }
}
