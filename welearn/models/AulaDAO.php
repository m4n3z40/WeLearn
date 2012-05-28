<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 10:20
 * To change this template use File | Settings | File Templates.
 */
 
class AulaDAO extends WeLearn_DAO_AbstractDAO
{
    const MAX_AULAS = 30;

    protected $_nomeCF = 'cursos_aula';

    private $_nomeAulaPorModuloCF = 'cursos_aula_por_modulo';

    /**
     * @var ColumnFamily
     */
    private $_aulaPorModuloCF;

    /**
     * @var ModuloDAO
     */
    private $_moduloDao;

    public function __construct()
    {
        $this->_aulaPorModuloCF = WL_Phpcassa::getInstance()
                                      ->getColumnFamily($this->_nomeAulaPorModuloCF);

        $this->_moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
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
            $count = AulaDAO::MAX_AULAS;
        }

        if ( isset($filtros['modulo'] ) &&
             ($filtros['modulo'] instanceof WeLearn_Cursos_Curso) ) {
            return $this->recuperarTodosPorModulo($filtros['modulo'],
                                                  $de,
                                                  $ate,
                                                  $count);
        }

        return array();
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @param string $de
     * @param string $ate
     * @param int $count
     */
    public function recuperarTodosPorModulo(WeLearn_Cursos_Conteudo_Modulo $modulo,
                                            $de = '',
                                            $ate = '',
                                            $count = AulaDAO::MAX_AULAS)
    {
        $moduloUUID = CassandraUtil::import( $modulo->getId() );

        $aulasIds = $this->_aulaPorModuloCF->get(
            $moduloUUID->bytes, null, $de, $ate, false, $count
        );

        $columns = $this->_cf->multiget($aulasIds);

        return $this->_criarVariosFromCassandra($columns);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
         if ($de instanceof WeLearn_Cursos_Conteudo_Modulo) {
             return $this->recuperarQtdTotalPorModulo($de);
         }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return int
     */
    public function recuperarQtdTotalPorModulo(WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $moduloUUID = CassandraUtil::import( $modulo->getId() );

        return $this->_aulaPorModuloCF->get_count($moduloUUID->bytes);
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $UUID = CassandraUtil::import($id);

        $aulaRemovida = $this->recuperar( $id );

        $moduloUUID = CassandraUtil::import( $aulaRemovida->getModulo()->getId() );

        $this->_cf->remove($UUID->bytes);
        $this->_aulaPorModuloCF->remove(
            $moduloUUID->bytes,
            array( $aulaRemovida->getNroOrdem() )
        );

        $aulaRemovida->setPersistido(false);

        return $aulaRemovida;
    }

     /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_Aula($dados);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import( $dto->getId() );

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $moduloUUID = CassandraUtil::import( $dto->getModulo()->getId() );

        $dto->setId( $UUID->string );
        $dto->setNroOrdem(
            $this->recuperarQtdTotalPorModulo( $dto->getModulo() ) + 1
        );

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());
        $this->_aulaPorModuloCF->insert(
            $moduloUUID->bytes,
            array( $dto->getNroOrdem() => $UUID->bytes )
        );

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @param array $novasPosicoes
     */
    public function atualizarPosicao(WeLearn_Cursos_Conteudo_Modulo $modulo,
                                     array $novasPosicoes)
    {
        $posicoes = array();
        $rows = array();

        foreach ($novasPosicoes as $posicao => $id) {
            $UUID = UUID::import( $id )->bytes;

            $posicoes[ $posicao ] = $UUID;

            $rows[ $UUID ] = array( 'nroOrdem' => $posicao );
        }

        $moduloUUID = UUID::import( $modulo->getId() )->bytes;

        $this->_cf->batch_insert( $rows );

        $this->_aulaPorModuloCF->remove( $moduloUUID );

        $this->_aulaPorModuloCF->insert( $moduloUUID, $posicoes );
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_Conteudo_Modulo $moduloPadrao = null)
    {
        $column['modulo'] = ($moduloPadrao instanceof WeLearn_Cursos_Conteudo_Modulo)
                            ? $moduloPadrao
                            : $this->_moduloDao->recuperar( $column['modulo'] );

        $aula = $this->criarNovo();
        $aula->fromCassandra($column);

        return $aula;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_Conteudo_Modulo $moduloPadra = null)
    {
        $listaAulas = array();

        foreach ($columns as $column) {
            $listaAulas[] = $this->_criarFromCassandra($column, $moduloPadra);
        }

        return $listaAulas;
    }
}
