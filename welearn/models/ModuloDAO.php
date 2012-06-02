<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 26/03/12
 * Time: 10:14
 * To change this template use File | Settings | File Templates.
 */
class ModuloDAO extends WeLearn_DAO_AbstractDAO
{
    const MAX_MODULOS = 12;

    protected $_nomeCF = 'cursos_modulo';

    private $_nomeModuloPorCursoCF = 'cursos_modulo_por_curso';

    /**
     * @var ColumnFamily|null
     */
    private $_moduloPorCursoCF;

    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $cursoUUID = CassandraUtil::import( $dto->getCurso()->getId() );

        $dto->setNroOrdem(
            $this->recuperarQtdTotalPorCurso( $dto->getCurso() ) + 1
        );
        $dto->setId( $UUID->string );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        $this->_moduloPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $dto->getNroOrdem() => $UUID->bytes )
        );

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import( $dto->getId() );

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @param array $novasPosicoes
     */
    public function atualizarPosicao(WeLearn_Cursos_Curso $curso,
                                     array $novasPosicoes)
    {
        $posicoes = array();
        $rows = array();

        foreach ($novasPosicoes as $posicao => $id) {
            $UUID = UUID::import( $id )->bytes;

            $posicoes[ $posicao ] = $UUID;

            $rows[ $UUID ] = array( 'nroOrdem' => $posicao );
        }

        $cursoUUID = UUID::import( $curso->getId() )->bytes;

        $this->_cf->batch_insert( $rows );

        $this->_moduloPorCursoCF->remove( $cursoUUID );

        $this->_moduloPorCursoCF->insert( $cursoUUID, $posicoes );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        if ( isset($filtros['count']) ) {
            $count = (int) $filtros['count'];
        } else {
            $count = ModuloDAO::MAX_MODULOS;
        }

        if ( isset($filtros['curso'] ) &&
             ($filtros['curso'] instanceof WeLearn_Cursos_Curso) ) {
            return $this->recuperarTodosPorCurso($filtros['curso'], $de, $ate, $count);
        }

        return array();
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
                                           $count = ModuloDAO::MAX_MODULOS)
    {
        $cursoUUID = CassandraUtil::import( $curso->getId() );

        $modulosIds = $this->_moduloPorCursoCF->get(
            $cursoUUID->bytes, null, $de, $ate, false, $count
        );

        $columns = $this->_cf->multiget( $modulosIds );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $UUID = CassandraUtil::import( $id );

        $column = $this->_cf->get( $UUID->bytes );

        return $this->_criarFromCassandra( $column );
    }

    /**
     * @param WeLearn_Cursos_Curso $doCurso
     * @param string $idAnterior
     * @return ModuloDAO
     */
    public function recuperarProximo(WeLearn_Cursos_Curso $doCurso, $idAnterior = '')
    {
        try {

            if ( $idAnterior != '' ) {

                $idAnterior = UUID::import( $idAnterior )->bytes;

                $count = 2;

            } else {

                $count = 1;

            }

            $moduloAtual = $this->recuperarTodosPorCurso(
                $doCurso,
                $idAnterior,
                '',
                $count
            );

            $key = $count - 1;

            return isset( $moduloAtual[ $key ] ) ? $moduloAtual[ $key ] : false;

        } catch (cassandra_NotFoundException $e) {

            return false;

        }
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Curso) {
            return $this->recuperarQtdTotalPorCurso($de);
        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = CassandraUtil::import( $curso->getId() );

        return $this->_moduloPorCursoCF->get_count($cursoUUID->bytes);
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $UUID = CassandraUtil::import( $id );

        $moduloRemovido = $this->recuperar( $id );

        $cursoUUID = CassandraUtil::import( $moduloRemovido->getCurso()->getId() );

        $this->_cf->remove( $UUID->bytes );
        $this->_moduloPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $moduloRemovido->getNroOrdem() )
        );

        $moduloRemovido->setPersistido(false);

        return $moduloRemovido;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_Modulo($dados);
    }

    public function __construct()
    {
        $this->_moduloPorCursoCF = WL_Phpcassa::getInstance()
             ->getColumnFamily($this->_nomeModuloPorCursoCF);

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        $column['curso'] = ($cursoPadrao instanceof WeLearn_Cursos_Curso)
                           ? $cursoPadrao
                           : $this->_cursoDao->recuperar( $column['curso'] );

        $modulo = $this->criarNovo();
        $modulo->fromCassandra($column);

        return $modulo;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        $listaModulos = array();

        foreach ($columns as $column) {
            $listaModulos[] = $this->_criarFromCassandra($column, $cursoPadrao);
        }

        return $listaModulos;
    }
}