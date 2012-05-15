<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 09/05/12
 * Time: 18:15
 * To change this template use File | Settings | File Templates.
 */

require_once __DIR__ . '/UsuarioDAO.php';

class GerenciadorAuxiliarDAO extends UsuarioDAO
{
    private $_nomeGerenciadoresPorCursoCF = 'cursos_gerenciador_por_curso';
    private $_nomeConvitesGerenciadorPorCursoCF = 'cursos_convite_gerenciador_por_curso';

    /**
     * @var ColumnFamily|null
     */
    private $_gerenciadoresPorCursoCF;

    /**
     * @var ColumnFamily|null
     */
    private $_convitesGerenciadorPorCursoCF;

    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    function __construct()
    {
        parent::__construct();

        $phpCassa = WL_Phpcassa::getInstance();

        $this->_gerenciadoresPorCursoCF = $phpCassa->getColumnFamily(
            $this->_nomeGerenciadoresPorCursoCF
        );

        $this->_convitesGerenciadorPorCursoCF = $phpCassa->getColumnFamily(
            $this->_nomeConvitesGerenciadorPorCursoCF
        );

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
    }

    public function vincular(WeLearn_Usuarios_Usuario $usuario,
                             WeLearn_Cursos_Curso $noCurso)
    {
        //TODO: Implementar este método.
    }

    public function desvincular(WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador,
                                WeLearn_Cursos_Curso $doCurso)
    {
        //TODO: Implementar este método.
    }

    public function convidar(WeLearn_Usuarios_Usuario $usuario,
                             WeLearn_Cursos_Curso $paraCurso)
    {
        //TODO: Implementar este método.
    }

    public function cancelarConvite(WeLearn_Usuarios_Usuario $usuario,
                                    WeLearn_Cursos_Curso $doCurso)
    {
        //TODO: Implementar este método.
    }

    public function aceitarConvite(WeLearn_Cursos_Curso $usuario,
                                   WeLearn_Cursos_Curso $doCurso)
    {

    }

    public function recusarConvite(WeLearn_Usuarios_Usuario $usuario,
                                   WeLearn_Cursos_Curso $doCurso)
    {
        //TODO: Implementar este método.
    }

    public function recuperarTodosPorCurso(WeLearn_Cursos_Curso $curso,
                                           $de = '',
                                           $ate = '',
                                           $count = 20)
    {
        //TODO: Implementar este método.
    }

    public function recuperarTodosConvitesPorCurso(WeLearn_Cursos_Curso $curso,
                                              $de = '',
                                              $ate = '',
                                              $count = 20)
    {
        //TODO: Implementar este método.
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        return $this->_gerenciadoresPorCursoCF->get_count( $cursoUUID->bytes );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalConvitesPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        return $this->_convitesGerenciadorPorCursoCF->get_count( $cursoUUID->bytes );
    }

    public function removerTodosPorCurso(WeLearn_Cursos_Curso $curso)
    {
        //TODO: Implementar este método.
    }

    private function _criarGerenciadorFromCassandra(array $column)
    {
        //TODO: Implementar este método.
    }

    private function _criarVariosGerenciadoresFromCassandra(array $column)
    {
        //TODO: Implementar este método.
    }
}
