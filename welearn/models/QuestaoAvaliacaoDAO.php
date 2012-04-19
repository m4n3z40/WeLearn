<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 19/04/12
 * Time: 08:36
 * To change this template use File | Settings | File Templates.
 */
class QuestaoAvaliacaoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_avaliacao_questao';

    private $_nomeQuestaoPorAvaliacaoCF = 'cursos_avaliacao_questao_por_avaliacao';

    /**
     * @var ColumnFamily
     */
    private $_questaoPorAvaliacaoCF;

    /**
     * @var AlternativaAvaliacaoDAO
     */
    private $_alternativaDAO;

    const MIN_QUESTOES = 1;
    const MAX_QUESTOES = 50;

    function __construct()
    {
        $this->_questaoPorAvaliacaoCF = WL_Phpcassa::getInstance()->getColumnFamily(
            $this->_nomeQuestaoPorAvaliacaoCF
        );

        $this->_alternativaDAO = WeLearn_DAO_DAOFactory::create('AlternativaAvaliacaoDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();

        $avaliacaoUUID = CassandraUtil::import( $dto->getAvaliacaoId() );

        $dto->setId( $UUID->string );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        $this->_questaoPorAvaliacaoCF->insert(
            $avaliacaoUUID->bytes,
            array( $UUID->bytes => '' )
        );

        $dto->setPersistido( true );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import( $dto->getId() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if (isset( $filtros['avaliacao'] )
            && $filtros['avaliacao'] instanceof WeLearn_Cursos_Avaliacoes_Avaliacao) {

            $count = isset( $filtros['count'] ) ? $filtros['count'] : QuestaoAvaliacaoDAO::MAX_QUESTOES;

            return $this->recuperarTodosPorAvaliacao(
                $filtros['avaliacao'],
                $de,
                $ate,
                $count
            );
        }

        return array();
    }

    public function recuperarTodosPorAvaliacao(
        WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao,
        $de = '',
        $ate = '',
        $count = QuestaoAvaliacaoDAO::MAX_QUESTOES
    )
    {
        if ( $de != '' ) {
            $de = CassandraUtil::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = CassandraUtil::import( $ate )->bytes;
        }

        $avaliacaoUUID = CassandraUtil::import( $avaliacao->getId() );

        $ids = $this->_questaoPorAvaliacaoCF->get(
            $avaliacaoUUID->bytes,
            null,
            $de,
            $ate,
            false,
            $count
        );

        $columns = $this->_cf->multiget( $ids );

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
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ( $de instanceof WeLearn_Cursos_Avaliacoes_Avaliacao ) {
            return $this->recuperarQtdTotalPorAvaliacao( $de );
        }

        return 0;
    }

    public function recuperarQtdTotalPorAvaliacao (
        WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
    )
    {
        $avaliacaoUUID = CassandraUtil::import( $avaliacao->getId() );
        return $this->_questaoPorAvaliacaoCF->get_count( $avaliacaoUUID->bytes );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $questaoRemovida = $this->recuperar( $id );

        $UUID = CassandraUtil::import( $questaoRemovida->getId() );
        $avaliacaoUUID = CassandraUtil::import( $questaoRemovida->getAvaliacaoId() );

        $this->_cf->remove( $UUID->bytes );
        $this->_questaoPorAvaliacaoCF->remove( $avaliacaoUUID->bytes, array( $UUID->bytes ) );

        $alternativasRemovidas = $this->_alternativaDAO->removerTodosPorQuestao( $id );

        $questaoRemovida->setAlternativas( $alternativasRemovidas );

        $questaoRemovida->setPersistido( false );

        return $questaoRemovida;
    }

    public function removerTodosPorAvaliacao (WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao)
    {
        try {
            $questoesRemovidas = $this->recuperarTodosPorAvaliacao( $avaliacao );
        } catch (cassandra_NotFoundException $e) {
            return array();
        }

        foreach ( $questoesRemovidas as $questao ) {
            $questaoUUID = UUID::import( $questao->getId() );

            $alternativasRemovidas = $this->_alternativaDAO->removerTodosPorQuestao( $questao );
            $this->_cf->remove( $questaoUUID->bytes );

            $questao->setAlternativas( $alternativasRemovidas );
            $questao->setPersistido( false );
        }

        $avaliacaoUUID = CassandraUtil::import( $avaliacao->getId() );
        $this->_questaoPorAvaliacaoCF->remove( $avaliacaoUUID->bytes );

        return $questoesRemovidas;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao($dados);
    }

    public  function recuperarAlternativas (
        WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao
    )
    {
        return $this->_alternativaDAO->recuperarTodosPorQuestao($questao);
    }

    private function _criarFromCassandra (array $column)
    {
        $questao = $this->criarNovo();
        $questao->fromCassandra( $column );

        return $questao;
    }

    private function _criarVariosFromCassandra (array $columns)
    {
        $listaQuestoes = array();

        foreach ($columns as $column) {
            $listaQuestoes[] = $this->_criarFromCassandra( $column );
        }

        return $listaQuestoes;
    }
}
