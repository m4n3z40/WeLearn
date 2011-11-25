<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 11/11/11
 * Time: 16:38
 * To change this template use File | Settings | File Templates.
 */
 
class VotoEnqueteDAO extends WeLearn_DAO_AbstractDAO {
    protected $_nomeCF = 'cursos_enquete_votos';

    private $_nomeVotosPorEnqueteSuperCF = 'cursos_enquete_votos_por_enquete';

    private $_votosPorEnquetesSuperCF;

    private $_enqueteDao;
    private $_usuarioDao;
    private $_alternativaEnqueteDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_votosPorEnquetesSuperCF = $phpCassa->getColumnFamily($this->_nomeVotosPorEnqueteSuperCF);

        $this->_enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_alternativaEnqueteDao = WeLearn_DAO_DAOFactory::create('AlternativaEnqueteDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $enqueteUUID = CassandraUtil::import($dto->getEnquete()->getId());
        $alternativaUUID = CassandraUtil::import($dto->getAlternativa()->getId());

        $dto->setId($UUID->string);
        $dto->setDataVoto(time());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $this->_votosPorEnquetesSuperCF->insert($enqueteUUID->bytes, array($alternativaUUID->bytes => array($UUID->bytes => '')));

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getId());

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
        if(isset($filtros['count'])) {
            $count = $filtros['count'];
        } else {
            $count = 10;
        }

        if (isset($filtros['alternativa']) && $filtros['alternativa'] instanceof WeLearn_Cursos_Enquetes_AlternativaEnquete) {
            return $this->recuperarTodosPorAlternativa($filtros['alternativa'], $de, $ate, $count);
        }

        if (isset($filtros['enquete']) && $filtros['alternativa'] instanceof WeLearn_Cursos_Enquetes_Enquete) {
            return $this->recuperarQtdTotalPorEnquete($filtros['enquete'], $de, $ate, $count);
        }

        return array();
    }

    public function recuperarTodosPorEnquete(WeLearn_Cursos_Enquetes_Enquete $enquete, $de = '', $ate = '', $count = 10)
    {
        if ( $de !=  '' ) {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ( $ate != '' ) {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $enqueteUUID = CassandraUtil::import($enquete->getId());

        $idsEnquetes = array();

        $indexes = $this->_votosPorEnquetesSuperCF->get($enqueteUUID->bytes, null, $de, $ate, false, $count);

        foreach ($indexes as $index) {
            foreach($index as $key => $val) {
                $idsEnquetes[] = $key;
            }
        }

        $columns = $this->_cf->multiget($idsEnquetes);

        return $this->_criarVariosFromCassandra($columns, $enquete);
    }

    public function recuperarTodosPorAlternativa(WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa, $de = '', $ate = '', $count = 10)
    {
        if ( $de !=  '' ) {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ( $ate != '' ) {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $enqueteUUID = CassandraUtil::import($alternativa->getEnqueteId());
        $alternativaUUID = CassandraUtil::import($alternativa->getId());

        $enquete = $this->_enqueteDao->recuperar($enqueteUUID);

        $idsEnquetes = array_keys(
            $this->_cf->get($enqueteUUID->bytes, null, $de, $ate, false, $count, $alternativaUUID->bytes)
        );

        $columns = $this->_cf->multiget($idsEnquetes);

        return $this->_criarVariosFromCassandra($columns, $enquete, $alternativa);
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
        if ( ($de instanceof WeLearn_Cursos_Enquetes_Enquete) && $ate instanceof WeLearn_Cursos_Enquetes_AlternativaEnquete) {
            return $this->recuperarQtdTotalPorAlternativa($de, $ate);
        }

        if ( $de instanceof WeLearn_Cursos_Enquetes_Enquete ) {
            return $this->recuperarQtdTotalPorEnquete($de);
        }

        return 0;
    }

    public function recuperarQtdTotalPorEnquete(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        $enqueteUUID = CassandraUtil::import($enquete->getId());

        return $this->_votosPorEnquetesSuperCF->get_count($enqueteUUID->bytes);
    }

    public function recuperarQtdTotalPorAlternativa(WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa)
    {
        $enqueteUUID = CassandraUtil::import($alternativa->getEnqueteId());
        $alternativaUUID = CassandraUtil::import($alternativa->getId());

        return $this->_votosPorEnquetesSuperCF->get_count($enqueteUUID->bytes, null, '', '', $alternativaUUID->bytes);
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

        $voto = $this->recuperar($id);

        $enqueteUUID = CassandraUtil::import($voto->getEnquete()->getId());
        $alternativa = CassandraUtil::import($voto->getAlternativa->getId());

        $this->_cf->remove($id->bytes);

        $this->_votosPorEnquetesSuperCF->remove($enqueteUUID->bytes, array($id->bytes), $alternativa->bytes);

        $voto->setPersistido(false);
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        $voto = new WeLearn_Cursos_Enquetes_VotoEnquete();
        $voto->preencherPropriedades($dados);

        return $voto;
    }

    public function _criarFromCassandra(array $column,
                                        WeLearn_Cursos_Enquetes_Enquete $enquetePadrao = null,
                                        WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativaPadrao = null)
    {
        if ( $enquetePadrao instanceof WeLearn_Cursos_Enquetes_Enquete ) {
            $column['enquete'] = $enquetePadrao;
        } else {
            $column['enquete'] = $this->_enqueteDao->recuperar($column['enquete']);
        }

        if ( $alternativaPadrao instanceof WeLearn_Cursos_Enquetes_AlternativaEnquete ) {
            $column['alternativa'] = $alternativaPadrao;
        } else {
            $column['alternativa'] = $this->_alternativaEnqueteDao->recuperar($column['alternativa']);
        }

        $column['votante'] = $this->_usuarioDao->recuperar($column['votante']);

        $voto = $this->criarNovo();

        $voto->fromCassandra($column);

        return $voto;
    }

    public function _criarVariosFromCassandra(array $columns,
                                              WeLearn_Cursos_Enquetes_Enquete $enquetePadrao = null,
                                              WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativaPadrao = null)
    {
        $listaVotos = array();

        foreach ( $columns as $column ) {
            $listaVotos[] = $this->_criarFromCassandra($column, $enquetePadrao, $alternativaPadrao);
        }

        return $listaVotos;
    }
}
