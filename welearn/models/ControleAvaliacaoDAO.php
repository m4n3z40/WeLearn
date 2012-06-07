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

    private $_controleAvaliacaoPorParticipacaoCF;
    private $_respostasAvaliacaoCF;

    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_controleAvaliacaoPorParticipacaoCF = $phpCassa->getColumnFamily(
            $this->_nomeControleAvaliacaoPorParticipacaoCF
        );

        $this->_respostasAvaliacaoCF = $phpCassa->getColumnFamily(
            $this->_nomeRespostasAvaliacaoCF
        );
    }

     /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implementar este metodo
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implementar este metodo
    }

    public function recuperarTodosPorParticipacao(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                                  $de = '',
                                                  $ate = '',
                                                  $count = 12)
    {
        // TODO: Implementar este metodo
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

        $this->_cf->remove($id);
        $this->_respostasAvaliacaoCF->remove($id);
        $this->_controleAvaliacaoPorParticipacaoCF->remove(
            $controleRemovido->getParticipacaoCurso()->getId(),
            array( $controleRemovido->getAvaliacao()->getModulo()->getNroOrdem() )
        );

        return $controleRemovido;
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

        if ( count( $dto->getRespostas() > 0 ) ) {

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
        // TODO: Implementar este metodo
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                               WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao = null)
    {
        // TODO: Implementar este metodo
    }

    private function _criarRespostasFromCassandra(array $column)
    {
        // TODO: Implementar este metodo
    }

    private function _criarVariasRespostasFromCassandra(array $columns)
    {
        // TODO: Implementar este metodo
    }
}
