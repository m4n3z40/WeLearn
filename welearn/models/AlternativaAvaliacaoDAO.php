<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 09/08/11
 * Time: 09:40
 * To change this template use File | Settings | File Templates.
 */
 
class AlternativaAvaliacaoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_avaliacao_alternativa';

    private $_nomeAlternativaPorQuestaoCF = 'cursos_avaliacao_alternativa_por_questao';

    /**
     * @var ColumnFamily|null
     */
    private $_alternativaPorQuestaoCF;

    const MIN_ALTERNATIVAS = 2;
    const MAX_ALTERNATIVAS = 12;

    function __construct()
    {
        $this->_alternativaPorQuestaoCF = WL_Phpcassa::getInstance()
                                                       ->getColumnFamily(
            $this->_nomeAlternativaPorQuestaoCF
        );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if (isset( $filtros['questao'] )
            && $filtros['questao'] instanceof WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao) {

            $count = isset( $filtros['count'] )
                     ? $filtros['count']
                     : AlternativaAvaliacaoDAO::MAX_ALTERNATIVAS;

            return $this->recuperarQtdTotalPorQuestao( $filtros['questao'], $de, $ate, $count );
        }

        return array();
    }

    public function recuperarTodosPorQuestao (
        WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao,
        $de = '',
        $ate = '',
        $count = AlternativaAvaliacaoDAO::MAX_ALTERNATIVAS
    )
    {
        if ($de != '') {
            $de = CassandraUtil::import( $de )->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import( $ate )->bytes;
        }

        $questaoUUID = CassandraUtil::import( $questao->getId() );

        $ids = $this->_alternativaPorQuestaoCF->get(
            $questaoUUID->bytes,
            null,
            $de,
            $ate,
            false,
            $count
        );

        $columns = $this->_cf->multiget( $ids );

        $this->_criarVariosfromCassandra( $columns );
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
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao) {
            return $this->recuperarQtdTotalPorQuestao( $de );
        }

        return 0;
    }

    public function recuperarQtdTotalPorQuestao (
        WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao
    )
    {
        $questaoUUID = CassandraUtil::import( $questao->getId() );
        return $this->_alternativaPorQuestaoCF->get_count( $questaoUUID->bytes );
    }

     /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $alternativaRemovida = $this->recuperar( $id );

        $UUID = CassandraUtil::import( $id );
        $questaoUUID = CassandraUtil::import( $alternativaRemovida->getQuestaoId() );

        $this->_cf->remove($UUID->bytes);
        $this->_alternativaPorQuestaoCF->remove( $questaoUUID->bytes, array($UUID->bytes) );

        $alternativaRemovida->setPersistido( false );

        return $alternativaRemovida;
    }

    public function removerTodosPorQuestao(WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao)
    {
        try {
            $alternativasRemovidas = $this->recuperarTodosPorQuestao( $questao );
        } catch (cassandra_NotFoundException $e) {
            return array();
        }

        foreach ($alternativasRemovidas as $alternativa) {
            $alternativaUUID = UUID::import( $alternativa->getId() );

            $this->_cf->remove( $alternativaUUID->bytes );

            $alternativa->setPersistido( false );
        }

        $questaoUUID = CassandraUtil::import( $questao->getId() );
        $this->_alternativaPorQuestaoCF->remove( $questaoUUID->bytes );

        return $alternativasRemovidas;
    }

     /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
       return new WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao($dados);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $questaoUUID = CassandraUtil::import( $dto->getQuestaoId() );

        $dto->setId( $UUID->string );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        $this->_alternativaPorQuestaoCF->insert(
            $questaoUUID->bytes,
            array( $UUID->bytes => '' )
        );

        $dto->setPersistido(true);
    }

     /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import( $dto->getId() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );
    }

    public function _criarFromCassandra (array $column)
    {
        $alternativa = $this->criarNovo();
        $alternativa->fromCassandra( $column );

        return $alternativa;
    }

    public function _criarVariosfromCassandra (array $columns)
    {
        $listaAlternativas = array();

        foreach ( $columns as $column ) {
            $listaAlternativas[] = $this->_criarFromCassandra( $column );
        }

        return $listaAlternativas;
    }
}
