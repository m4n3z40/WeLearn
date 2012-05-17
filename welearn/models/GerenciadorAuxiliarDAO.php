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

    /**
     *
     */
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

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     * @return WeLearn_Usuarios_GerenciadorAuxiliar
     */
    public function vincular(WeLearn_Usuarios_Usuario $usuario,
                             WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $gerenciador = $this->criarGerenciadorAuxiliar( $usuario );

        $this->_gerenciadoresPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $gerenciador->getId() => '' )
        );

        $this->_cursoDao->salvarGerenciador( $gerenciador, $noCurso );

        return $gerenciador;
    }

    /**
     * @param WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function desvincular(WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador,
                                WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::import( $doCurso->getId() );

        $this->_gerenciadoresPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $gerenciador->getId() )
        );

        $this->_cursoDao->removerGerenciador( $gerenciador, $doCurso );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $paraCurso
     */
    public function convidar(WeLearn_Usuarios_Usuario $usuario,
                             WeLearn_Cursos_Curso $paraCurso)
    {
        $cursoUUID = UUID::import( $paraCurso->getId() );

        $this->_convitesGerenciadorPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $usuario->getId() => '' )
        );

        $this->_cursoDao->salvarConviteGerenciador( $usuario, $paraCurso );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function cancelarConvite(WeLearn_Usuarios_Usuario $usuario,
                                    WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::import( $doCurso->getId() );

        $this->_convitesGerenciadorPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $usuario->getId() )
        );

        $this->_cursoDao->removerConviteGerenciador( $usuario, $doCurso );
    }

    /**
     * @param WeLearn_Cursos_Curso $usuario
     * @param WeLearn_Cursos_Curso $doCurso
     * @return WeLearn_Usuarios_GerenciadorAuxiliar
     */
    public function aceitarConvite(WeLearn_Cursos_Curso $usuario,
                                   WeLearn_Cursos_Curso $doCurso)
    {
        $this->cancelarConvite( $usuario, $doCurso );

        return $this->vincular( $usuario, $doCurso );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function recusarConvite(WeLearn_Usuarios_Usuario $usuario,
                                   WeLearn_Cursos_Curso $doCurso)
    {
        $this->cancelarConvite( $usuario, $doCurso );
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
        $cursoUUID = UUID::import( $curso->getId() );

        $ids = array_keys(
            $this->_gerenciadoresPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                $de,
                $ate,
                false,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosGerenciadoresFromCassandra( $columns );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosConvitesPorCurso(WeLearn_Cursos_Curso $curso,
                                              $de = '',
                                              $ate = '',
                                              $count = 20)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        $ids = array_keys(
            $this->_convitesGerenciadorPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                $de,
                $ate,
                false,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosGerenciadoresFromCassandra( $columns );
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

    /**
     * @param WeLearn_Cursos_Curso $curso
     */
    public function removerTodosPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        $idsGerenciadores = array_keys(
            $this->_gerenciadoresPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                $de,
                $ate,
                false,
                $count
            )
        );

        $idsConvites = array_keys(
            $this->_convitesGerenciadorPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                $de,
                $ate,
                false,
                $count
            )
        );

        $this->_gerenciadoresPorCursoCF->remove( $cursoUUID->bytes );
        $this->_convitesGerenciadorPorCursoCF->remove( $cursoUUID->bytes );

        $this->_cursoDao->removerTodosGerenciadores( $idsGerenciadores, $curso );
        $this->_cursoDao->removerTodosConvitesGerenciador( $idsConvites, $curso );
    }

    /**
     * @param array $column
     * @return WeLearn_Usuarios_GerenciadorAuxiliar
     */
    private function _criarGerenciadorFromCassandra(array $column)
    {
        $column['segmentoInteresse'] = $this->_segmentoDao->recuperar(
            $column['segmentoInteresse']
        );

        $gerenciador = $this->criarGerenciadorAuxiliar();

        $gerenciador->fromCassandra( $column );

        return $gerenciador;
    }

    /**
     * @param array $columns
     * @return array
     */
    private function _criarVariosGerenciadoresFromCassandra(array $columns)
    {
        $listaGerenciadores = array();

        foreach ($columns as $column) {
            $listaGerenciadores[] = $this->_criarGerenciadorFromCassandra( $column );
        }

        return $listaGerenciadores;
    }
}
