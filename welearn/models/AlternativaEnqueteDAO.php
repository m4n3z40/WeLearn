<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Suporte Técnico
 * Date: 09/08/11
 * Time: 09:52
 * To change this template use File | Settings | File Templates.
 */
 
class AlternativaEnqueteDAO extends WeLearn_DAO_AbstractDAO{

    protected $_nomeCF = 'cursos_enquete_alternativa';

    private $_nomeAlternativaPorEnqueteCF = 'cursos_enquete_alternativa_por_enquete';

    private $_alternativaPorEnqueteCF;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_alternativaPorEnqueteCF = $phpCassa->getColumnFamily($this->_nomeAlternativaPorEnqueteCF);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if ( isset($filtros['count']) ) {
            $count = $filtros['count'];
        } else {
            $count = 10;
        }

        if ( isset($filtros['enquete']) && $filtros['enquete'] instanceof WeLearn_Cursos_Enquetes_Enquete ) {
            return $this->recuperarTodosPorEnquete($filtros['enquete'], $de, $ate, $count);
        }

        return array();
    }

    public function recuperarTodosPorEnquete(WeLearn_Cursos_Enquetes_Enquete $enquete, $de = '', $ate = '', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $enqueteUUID = CassandraUtil::import($enquete->getId());

        $idsAlternativas = array_keys(
            $this->_alternativaPorEnqueteCF->get($enqueteUUID->bytes, null, $de, $ate, false, $count)
        );

        $columns = $this->_cf->multiget($idsAlternativas);

        return $this->_criarVariosFromCassandra($columns);
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        if ( ! ($id instanceof UUID) ) {
            $id = CassandraUtil::import($id);
        }

        $column = $this->_cf->get($id->bytes);

        return $this->_criarFromCassandra($column);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Enquetes_Enquete) {
            return $this->recuperarQtdTotal($de);
        }

        return 0;
    }

    public function recuperarQtdTotalPorEnquete(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        $enqueteUUID = CassandraUtil::import($enquete->getId());

        return $this->_alternativaPorEnqueteCF->get_count($enqueteUUID->bytes);
    }

     /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        if ( ! ($id instanceof UUID) ) {
            $id = CassandraUtil::import($id);
        }

        $alternativa = $this->recuperar($id);

        $enqueteUUID = CassandraUtil::import($alternativa->getEnqueteId());

        $this->_cf->remove($id->bytes);

        $this->_alternativaPorEnqueteCF->remove($enqueteUUID->bytes, array($id->bytes));

        $alternativa->setPersistido(false);

        return $alternativa;
    }

     /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        $alternativa = new WeLearn_Cursos_Enquetes_AlternativaEnquete();
        $alternativa->preencherPropriedades($dados);

        return $alternativa;
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $enqueteUUID = CassandraUtil::import($dto->getEnqueteId());

        $dto->setId($UUID->string);

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $this->_alternativaPorEnqueteCF->insert($enqueteUUID->bytes, array($UUID->bytes => ''));

        $dto->setPersistido(true);
    }

     /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getId());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());
    }

    /**
     * @param WeLearn_Cursos_Enquetes_AlternativaEnquete $AlternativaEnquete
     * @return int
     */
    public function recuperarQtdVotos(WeLearn_Cursos_Enquetes_AlternativaEnquete $AlternativaEnquete)
    {
        /*
         * implementar metodo
         */
    }

    private function _criarFromCassandra(array $column)
    {
        $alternativa = $this->criarNovo();

        $alternativa->fromCassandra($column);

        return $alternativa;
    }

    private function _criarVariosFromCassandra(array $columns)
    {
        $listaAlternativas = array();

        foreach ($columns as $column) {
            $listaAlternativas[] = $this->_criarFromCassandra($column);
        }

        return $listaAlternativas;
    }
}