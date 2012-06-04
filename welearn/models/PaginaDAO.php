<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 13/04/12
 * Time: 21:30
 * To change this template use File | Settings | File Templates.
 */
class PaginaDAO extends WeLearn_DAO_AbstractDAO
{
    const MAX_PAGINAS = 50;

    protected $_nomeCF = 'cursos_pagina';

    private $_nomePaginaPorAulaCF = 'cursos_pagina_por_aula';

    private $_nomeContador = 'contadores_timeuuid';
    private $_keyContador = 'cursos_conteudo_total_paginas';

    /**
     * @var ColumnFamily
     */
    private $_paginaPorAulaCF;

    /**
     * @var ColumnFamily
     */
    private $_contadorCF;

    /**
     * @var AulaDAO
     */
    private $_aulaDAO;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_paginaPorAulaCF = $phpCassa->getColumnFamily(
            $this->_nomePaginaPorAulaCF
        );

        $this->_contadorCF = $phpCassa->getColumnFamily(
            $this->_nomeContador
        );

        $this->_aulaDAO = WeLearn_DAO_DAOFactory::create('AulaDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $aulaUUID = CassandraUtil::import( $dto->getAula()->getId() );

        $dto->setId( $UUID->string );
        $dto->setNroOrdem( $this->recuperarQtdTotalPorAula( $dto->getAula() ) + 1 );

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $this->_paginaPorAulaCF->insert(
            $aulaUUID->bytes,
            array(
                $dto->getNroOrdem() => $UUID->bytes
            )
        );

        $cursoUUID = CassandraUtil::import(
            $dto->getAula()->getModulo()->getCurso()->getId()
        );
        $this->_contadorCF->add($this->_keyContador, $cursoUUID->bytes);

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
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        $count = ( isset( $filtros['count'] ) )
                   ? $filtros['count']
                   : self::MAX_PAGINAS;

        if ( isset( $filtros['aula'] ) ) {
            return $this->recuperarTodosPorAula($filtros['aula'], $de, $ate, $count);
        }

        return array();
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @param string $de
     * @param string $ate
     * @param int $count
     * @param bool $invertido
     * @return array
     */
    public function recuperarTodosPorAula(WeLearn_Cursos_Conteudo_Aula $aula,
                                          $de = '',
                                          $ate = '',
                                          $count = self::MAX_PAGINAS,
                                          $invertido = false)
    {
        $aulaUUID = CassandraUtil::import( $aula->getId() );

        $idsPaginas = $this->_paginaPorAulaCF->get(
            $aulaUUID->bytes,
            null,
            $de,
            $ate,
            $invertido,
            $count
        );

        $columns = $this->_cf->multiget( $idsPaginas );

        return $this->_criarVariosFromCassandra( $columns, $aula );
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
     * @param WeLearn_Cursos_Conteudo_Aula $daAula
     * @param int $nroOrdem
     * @return WeLearn_Cursos_Conteudo_Pagina
     */
    public function recuperarPorOrdem(WeLearn_Cursos_Conteudo_Aula $daAula, $nroOrdem)
    {
        $aulaUUID = UUID::import( $daAula->getId() )->bytes;

        $idPagina = $this->_paginaPorAulaCF->get( $aulaUUID, array( (int)$nroOrdem ) );
        $idPagina = $idPagina[ $nroOrdem ];

        $column = $this->_cf->get( $idPagina );

        return $this->_criarFromCassandra( $column, $daAula );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $daAula
     * @param int $ordemAnterior
     * @return bool|WeLearn_Cursos_Conteudo_Pagina
     */
    public function recuperarProxima(WeLearn_Cursos_Conteudo_Aula $daAula, $ordemAnterior = 0)
    {
        try {

            return $this->recuperarPorOrdem( $daAula, (int)$ordemAnterior + 1 );

        } catch (cassandra_NotFoundException $e) {

            return false;

        }
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $daAula
     * @param int $ordemAtual
     * @return bool|WeLearn_Cursos_Conteudo_Pagina
     */
    public function recuperarAnterior(WeLearn_Cursos_Conteudo_Aula $daAula, $ordemAtual = self::MAX_PAGINAS) {

        try {

            return $this->recuperarPorOrdem( $daAula, (int)$ordemAtual - 1 );

        } catch ( cassandra_NotFoundException $e ) {

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
        if ( $de instanceof WeLearn_Cursos_Conteudo_Aula ) {
            return $this->recuperarQtdTotalPorAula( $de );
        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return int
     */
    public function recuperarQtdTotalPorAula(WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $aulaUUID = CassandraUtil::import( $aula->getId() );

        return $this->_paginaPorAulaCF->get_count( $aulaUUID->bytes );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = CassandraUtil::import( $curso->getId() );

        try {
            $column = $this->_contadorCF->get($this->_keyContador, array($cursoUUID->bytes));
            $totalPaginas = $column[ $cursoUUID->bytes ];
        } catch (cassandra_NotFoundException $e) {
            $totalPaginas = 0;
        }

        return $totalPaginas;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @param array $novasPosicoes
     */
    public function atualizarPosicoes(WeLearn_Cursos_Conteudo_Aula $aula,
                                      array $novasPosicoes)
    {
        $posicoes = array();
        $rows = array();

        foreach ($novasPosicoes as $posicao => $id) {
            $UUID = UUID::import( $id )->bytes;

            $posicoes[ $posicao ] = $UUID;

            $rows[ $UUID ] = array( 'nroOrdem' => $posicao );
        }

        $aulaUUID = UUID::import( $aula->getId() )->bytes;

        $this->_cf->batch_insert( $rows );

        $this->_paginaPorAulaCF->remove( $aulaUUID );

        $this->_paginaPorAulaCF->insert( $aulaUUID, $posicoes );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $UUID = CassandraUtil::import( $id );

        $paginaRemovida = $this->recuperar( $id );

        $aulaUUID = CassandraUtil::import( $paginaRemovida->getAula()->getId() );

        $this->_cf->remove($UUID->bytes);
        $this->_paginaPorAulaCF->remove(
            $aulaUUID->bytes,
            array(
                $paginaRemovida->getNroOrdem()
            )
        );

        $cursoUUID = CassandraUtil::import(
            $paginaRemovida->getAula()->getModulo()->getCurso()->getId()
        );
        $this->_contadorCF->add($this->_keyContador, $cursoUUID->bytes, -1);

        $paginaRemovida->setPersistido(false);

        return $paginaRemovida;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_Pagina($dados);
    }

    private function _criarFromCassandra(array $column, WeLearn_Cursos_Conteudo_Aula $aulaPadrao = null)
    {
        $column['aula'] = ($aulaPadrao instanceof WeLearn_Cursos_Conteudo_Aula)
                           ? $aulaPadrao
                           : $this->_aulaDAO->recuperar( $column['aula'] );

        $pagina = $this->criarNovo();
        $pagina->fromCassandra($column);

        return $pagina;
    }

    private function _criarVariosFromCassandra(array $columns, WeLearn_Cursos_Conteudo_Aula $aulaPadrao = null)
    {
        $listaPaginas = array();

        foreach ($columns as $column) {
            $listaPaginas[] = $this->_criarFromCassandra( $column, $aulaPadrao );
        }

        return $listaPaginas;
    }
}
