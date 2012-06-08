<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 11:06
 * To change this template use File | Settings | File Templates.
 */
 
class ControleAvaliacaoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_controle_avaliacao';

    private $_nomeControleAvaliacaoPorParticipacaoCF = 'cursos_controle_avaliacao_por_participacao';
    private $_nomeRespostasAvaliacaoCF = 'cursos_controle_avaliacao_respostas';

    /**
     * @var ColumnFamily|null
     */
    private $_controleAvaliacaoPorParticipacaoCF;

    /**
     * @var ColumnFamily|null
     */
    private $_respostasAvaliacaoCF;

    /**
     * @var AvaliacaoDAO
     */
    private $_avaliacaoDao;

    /**
     * @var AlternativaAvaliacaoDAO
     */
    private $_alternativasDao;

    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_controleAvaliacaoPorParticipacaoCF = $phpCassa->getColumnFamily(
            $this->_nomeControleAvaliacaoPorParticipacaoCF
        );

        $this->_respostasAvaliacaoCF = $phpCassa->getColumnFamily(
            $this->_nomeRespostasAvaliacaoCF
        );

        $this->_alternativasDao = WeLearn_DAO_DAOFactory::create('AlternativaAvaliacaoDAO');
        $this->_avaliacaoDao    = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
     * @return bool
     */
    public function avaliacaoFeita(
        WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
        WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
    ) {
        try {

            $cfKey = WeLearn_Cursos_Avaliacoes_ControleAvaliacao::gerarCFKey(
                $participacaoCurso,
                $avaliacao
            );

            $this->_cf->get( $cfKey );

            return true;

        } catch (cassandra_NotFoundException $e) {

            return false;

        }
    }

     /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        if ( isset( $id['participacaoCurso'] ) && isset( $id['avaliacao'] ) ) {

            return $this->recuperar( $id['participacaoCurso'], $id['avaliacao'] );

        }

        return null;
    }

    public function recuperarPorParticipacao(
        WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
        WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
    ) {
        $cfKey = WeLearn_Cursos_Avaliacoes_ControleAvaliacao::gerarCFKey(
            $participacaoCurso,
            $avaliacao
        );

        $column = $this->_cf->get( $cfKey );

        return $this->_criarFromCassandra( $column, $participacaoCurso, $avaliacao );
    }

    /**
     * @param WeLearn_Cursos_Avaliacoes_ControleAvaliacao $controleAvaliacao
     * @return bool
     */
    public function recuperarRespostas(WeLearn_Cursos_Avaliacoes_ControleAvaliacao &$controleAvaliacao)
    {
        try {

            $ids = $this->_respostasAvaliacaoCF->get( $controleAvaliacao->getId() );

            $respostas = $this->_alternativasDao->recuperarTodosPorUUIDs( $ids );

            $controleAvaliacao->setRespostas( $respostas );

            return true;

        } catch (cassandra_NotFoundException $e) {

            return false;

        }
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        if ( isset( $filtros['count'] ) ) {
            $count = $filtros['count'];
        } else {
            $count = 12;
        }

        if ( isset( $filtros['participacaoCurso'] ) ) {

            return $this->recuperarTodosPorParticipacao(
                $filtros['participacaoCurso'],
                $de,
                $ate,
                $count
            );

        }

        return array();
    }

    public function recuperarTodosPorParticipacao(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                                  $de = '',
                                                  $ate = '',
                                                  $count = 12)
    {
        $ids = $this->_controleAvaliacaoPorParticipacaoCF->get(
            $participacaoCurso->getId(),
            null,
            $de,
            $ate,
            false,
            $count
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns, $participacaoCurso );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ( $de instanceof WeLearn_Cursos_ParticipacaoCurso ) {

            return $this->recuperarQtdTotalPorParticipacao( $de );

        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return int
     */
    public function recuperarQtdTotalPorParticipacao(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        return $this->_controleAvaliacaoPorParticipacaoCF->get_count(
            $participacaoCurso->getId()
        );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $controleRemovido = $this->recuperar( $id );

        $id = $controleRemovido->getId();

        $this->_cf->remove($id);
        $this->_respostasAvaliacaoCF->remove($id);
        $this->_controleAvaliacaoPorParticipacaoCF->remove(
            $controleRemovido->getParticipacaoCurso()->getId(),
            array( $controleRemovido->getAvaliacao()->getModulo()->getNroOrdem() )
        );

        return $controleRemovido;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
     * @return WeLearn_DTO_IDTO
     */
    public function removerPorParticipacao(
        WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
        WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
    ) {
        $arrayRemover = array(
            'participacaoCurso' => $participacaoCurso,
            'avaliacao' => $avaliacao
        );

        return $this->remover( $arrayRemover );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Avaliacoes_ControleAvaliacao($dados);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $this->_cf->insert( $dto->getId(), $dto->toCassandra() );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $this->_cf->insert( $dto->getId(), $dto->toCassandra() );

        $this->_controleAvaliacaoPorParticipacaoCF->insert(
            $dto->getParticipacaoCurso()->getId(),
            array(
                $dto->getAvaliacao()->getModulo()->getNroOrdem() => $dto->getId()
            )
        );

        if ( count( $dto->getRespostas() ) > 0 ) {

            $this->_respostasAvaliacaoCF->insert(
                $dto->getId(),
                $dto->respostasToCassandra()
            );

        }

        $dto->setPersistido(true);
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                         WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao = null)
    {
        $column['participacaoCurso'] = $participacaoCurso;

        $column['avaliacao'] = ( $avaliacao instanceof WeLearn_Cursos_Avaliacoes_Avaliacao )
                               ? $avaliacao
                               : $this->_avaliacaoDao->recuperar( $column['avaliacao'] );

        $controleAvaliacao = $this->criarNovo();
        $controleAvaliacao->fromCassandra( $column );

        return $controleAvaliacao;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                               WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao = null)
    {
        $listaControles = array();

        foreach( $columns as $column ) {

            $listaControles[] = $this->_criarFromCassandra(
                $column,
                $participacaoCurso,
                $avaliacao
            );

        }

        return $listaControles;
    }
}
