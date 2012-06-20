<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 10:42
 * To change this template use File | Settings | File Templates.
 */
 
class AvaliacaoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_avaliacao';

    /**
     * @var ModuloDAO
     */
    private $_moduloDao;

    /**
     * @var QuestaoAvaliacaoDAO
     */
    private $_questaoDao;

    function __construct()
    {
        $this->_moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
        $this->_questaoDao = WeLearn_DAO_DAOFactory::create('QuestaoAvaliacaoDAO');
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
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if ( isset($filtros['modulo']) ) {
            return array( $this->recuperar( $filtros['modulo']->getId() ) );
        }

        return array();
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ( $de instanceof WeLearn_Cursos_Conteudo_Modulo ) {
            try {
                $this->_cf->get( CassandraUtil::import( $de->getId() )->bytes );

                return 1;
            } catch (cassandra_NotFoundException $e) {
                return 0;
            }
        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        try {

            $modulos = $this->_moduloDao->recuperarTodosPorCurso( $curso );

            $qtdTotal = 0;

            foreach ($modulos as $modulo) {

                try {

                    $this->_cf->get( UUID::import( $modulo->getId() )->bytes );

                    $qtdTotal++;

                } catch ( cassandra_NotFoundException $e ) { }

            }

            return $qtdTotal;

        } catch ( cassandra_NotFoundException $e ) {

            return 0;

        }
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return bool
     */
    public function existeAvaliacao(WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        try {
            $this->_cf->get( CassandraUtil::import( $modulo->getId() )->bytes );

            return true;
        } catch (cassandra_NotFoundException $e) {
            return false;
        }
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $UUID = CassandraUtil::import( $id );

        $avaliacaoRemovida = $this->recuperar( $id );

        $questoesRemovidas = $this->_questaoDao->removerTodosPorAvaliacao( $avaliacaoRemovida );
        $this->_cf->remove( $UUID->bytes );

        $avaliacaoRemovida->setQuestoes( $questoesRemovidas );
        $avaliacaoRemovida->setPersistido( false );

        return $avaliacaoRemovida;
    }

     /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Avaliacoes_Avaliacao($dados);
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
        $dto->setId( $dto->getModulo()->getId() );

        $UUID = CassandraUtil::import( $dto->getId() );

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_Cursos_Avaliacoes_Avaliacao $Avaliacao
     * @return Array
     */
    public function recuperarQuestoes(WeLearn_Cursos_Avaliacoes_Avaliacao $Avaliacao)
    {
        return $this->_questaoDao->recuperarTodosPorAvaliacao($Avaliacao);
    }

    /**
     * @return \QuestaoAvaliacaoDAO
     */
    public function getQuestaoDao()
    {
        return $this->_questaoDao;
    }

    private  function _criarFromCassandra (array $column,
                                           WeLearn_Cursos_Conteudo_Modulo $moduloPadrao = null)
    {
        $column['modulo'] = ($moduloPadrao instanceof WeLearn_Cursos_Conteudo_Modulo)
                            ? $moduloPadrao
                            : $this->_moduloDao->recuperar( $column['modulo'] );

        $avaliacao = $this->criarNovo();

        $avaliacao->fromCassandra( $column );

        return $avaliacao;
    }
}
