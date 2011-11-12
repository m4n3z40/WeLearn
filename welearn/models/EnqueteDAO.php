<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 11/08/11
 * Time: 20:06
 * To change this template use File | Settings | File Templates.
 */
 
class EnqueteDAO extends WeLearn_DAO_AbstractDAO {
    protected $_nomeCF = 'cursos_enquete';

    private $_nomeEnquetePorCursoCF = 'cursos_enquete_por_curso';
    private $_nomeEnquetePorStatusSuperCF = 'cursos_enquete_por_status';
    private $_nomeEnquetePorSituacaoSuperCF = 'cursos_enquete_por_situacao';

    private $_enquetePorCursoCF;
    private $_enquetePorStatusSuperCF;
    private $_enquetePorSituacaoSuperCF;

    private $_cursoDao;
    private $_usuarioDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_enquetePorCursoCF = $phpCassa->getColumnFamily($this->_nomeEnquetePorCursoCF);
        $this->_enquetePorStatusSuperCF = $phpCassa->getColumnFamily($this->_nomeEnquetePorStatusSuperCF);
        $this->_enquetePorSituacaoSuperCF = $phpCassa->getColumnFamily($this->_nomeEnquetePorSituacaoSuperCF);

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
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
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if (isset($filtros['count'])) {
            $count = $filtros['count'];
        } else {
            $count = 10;
        }

        if ( (isset($filtros['curso']) && $filtros['curso'] instanceof WeLearn_Cursos_Curso) && isset($filtros['status']) ) {
            return $this->recuperarTodosPorStatus($filtros['curso'], $filtros['status'], $de, $ate, $count);
        }

        if ( (isset($filtros['curso']) && $filtros['curso'] instanceof WeLearn_Cursos_Curso) && isset($filtros['situacao']) ) {
            return $this->recuperarTodosPorSituacao($filtros['curso'], $filtros['situacao'], $de, $ate, $count);
        }

        if ( isset($filtros['curso']) && $filtros['curso'] instanceof WeLearn_Cursos_Curso ) {
            return $this->recuperarTodosPorCurso($filtros['curso'], $de, $ate, $count);
        }

        return array();
    }

    public function recuperarTodosPorCurso(WeLearn_Cursos_Curso $curso, $de = '', $ate ='', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $cursoUUID = CassandraUtil::import($curso->getId());

        $idsEnquetes = array_keys(
            $this->_enquetePorCursoCF->get($cursoUUID->bytes, null, $de, $ate, true, $count)
        );

        $columns = $this->_cf->multiget($idsEnquetes);

        return $this->_criarVariosFromCassandra($columns);
    }

    public function recuperarTodosPorStatus(WeLearn_Cursos_Curso $curso, $status, $de = '', $ate = '', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $cursoUUID = CassandraUtil::import($curso->getId());

        $idsEnquetes = array_keys(
            $this->_enquetePorStatusSuperCF->get($cursoUUID->bytes, null, $de, $ate, true, $count, $status)
        );

        $columns = $this->_cf->multiget($idsEnquetes);

        return $this->_criarVariosFromCassandra($columns, $curso);
    }

    public function recuperarTodosPorSituacao(WeLearn_Cursos_Curso $curso, $situacao, $de = '', $ate = '', $count = 10)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $cursoUUID = CassandraUtil::import($curso->getId());

        $idsEnquetes = array_keys(
            $this->_enquetePorSituacaoSuperCF->get($cursoUUID->bytes, null, $de, $ate, true, $count, $situacao)
        );

        $columns = $this->_cf->multiget($idsEnquetes);

        return $this->_criarVariosFromCassandra($columns, $curso);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Curso && is_int($ate)) {
            return $this->recuperarQtdTotalPorStatus($de, $ate);
        }

        if ($de instanceof WeLearn_Cursos_Curso) {
            return $this->recuperarQtdTotalPorCurso($de);
        }

        return 0;
    }

    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = CassandraUtil::import($curso->getId());

        return $this->_enquetePorCursoCF->get_count($cursoUUID->bytes);
    }

    public function recuperarQtdTotalPorStatus(WeLearn_Cursos_Curso $curso, $status)
    {
        $cursoUUID = CassandraUtil::import($curso->getId());

        return $this->_enquetePorStatusSuperCF->get_count($cursoUUID->bytes, null, '', '', $status);
    }

    public function recuperarQtdTotalPorSituacao(WeLearn_Cursos_Curso $curso, $situacao)
    {
        $cursoUUID = CassandraUtil::import($curso->getId());

        return $this->_enquetePorSituacaoSuperCF->get_count($cursoUUID->bytes, null, '', '', $situacao);
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

        $enquete = $this->recuperar($id);

        $cursoUUID = CassandraUtil::import($enquete->getCurso()->getId());

        $this->_cf->remove($id->bytes);

        $this->_enquetePorCursoCF->remove($cursoUUID->bytes, array($id->bytes));
        $this->_enquetePorStatusSuperCF->remove($cursoUUID->bytes, array($id->bytes), $enquete->getStatus());
        $this->_enquetePorSituacaoSuperCF->remove($cursoUUID->bytes, array($id->bytes), $enquete->getStuacao());

        $enquete->setPersistido(false);

        return $enquete;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Enquetes_Enquete($dados);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $cursoUUID = CassandraUtil::import($dto->getCurso()->getId());

        $dto->setId($UUID->string);
        $dto->setDataCriacao(time());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $this->_enquetePorCursoCF->insert($cursoUUID->bytes, array($UUID->bytes => ''));
        $this->_enquetePorStatusSuperCF->insert($cursoUUID->bytes, array($dto->getStatus() => array($UUID->bytes => '')));
        $this->_enquetePorSituacaoSuperCF->insert($cursoUUID->bytes, array($dto->getSituacao() => array($UUID->bytes => '')));

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getId());
        $cursoUUID = CassandraUtil::import($dto->getCurso()->getId());

        $dadosAntigos = $this->_cf->get($UUID->bytes, array('status', 'situacao'));
        $statusAntigo = (int) $dadosAntigos['status'];
        $situacaoAntiga = (int) $dadosAntigos['situacao'];

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        if ($statusAntigo != $dto->getStatus()) {
            $this->_enquetePorStatusSuperCF->remove($cursoUUID->bytes, array($UUID->bytes), $statusAntigo);

            $this->_enquetePorStatusSuperCF->insert($cursoUUID->bytes, array($dto->getStatus() => array($UUID->bytes => '')));
        }

        if ($situacaoAntiga != $dto->getSituacao()) {
            $this->_enquetePorSituacaoSuperCF->remove($cursoUUID->bytes, array($UUID->bytes), $situacaoAntiga);

            $this->_enquetePorSituacaoSuperCF->insert($cursoUUID->bytes, array($dto->getSituacao() => array($UUID->bytes => '')));
        }
    }

    /**
     * @param array $dadosAlterantiva
     * @return void
     */
    public function criarAlternativas(array $dadosAlterantiva)
    {
         // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return void
     */
    public function recuperarAlternativas(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
         // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return int
     */
    public function recuperarQtdTotalVotos(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
         // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Enquetes_Enquete $enquete
     * @return void
     */
    public function zerarVotos(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        // TODO: Implementar este metodo.
    }

    private function _criarFromCassandra(array $column, WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        if ($cursoPadrao instanceof WeLearn_Cursos_Curso) {
            $column['curso'] = $cursoPadrao;
        } else {
            $column['curso'] = $this->_cursoDao->recuperar($column['curso']);
        }

        $column['criador'] = $this->_usuarioDao->recuperar($column['criador']);

        $enquete = $this->criarNovo();

        $enquete->fromCassandra($column);

        return $enquete;
    }

    private function _criarVariosFromCassandra(array $columns, WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        $listaEnquetes = array();

        foreach($columns as $column) {
            $listaEnquetes[] = $this->_criarFromCassandra($column, $cursoPadrao);
        }

        return $listaEnquetes;
    }
}
