<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 06/05/12
 * Time: 17:06
 * To change this template use File | Settings | File Templates.
 */
class RespostaResenhaDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_resenha_resposta';

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    function __construct()
    {
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::import( $dto->getResenhaId() );

        $dto->setDataEnvio( time() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        $dto->setPersistido( true );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = UUID::import( $dto->getResenhaId() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        return array();
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $UUID = UUID::import( $id );

        $column = $this->_cf->get($UUID->bytes);

        return $this->_criarFromCassandra($column);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        return 0;
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $UUID = UUID::import( $id );

        $respostaRemovida = $this->recuperar( $id );

        $this->_cf->remove($UUID->bytes);

        $respostaRemovida->setPersistido(false);

        return $respostaRemovida;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Reviews_RespostaResenha($dados);
    }

    private function _criarFromCassandra(array $column)
    {
        $column['criador'] = $this->_usuarioDao->recuperar( $column['criador'] );

        $resposta = $this->criarNovo();
        $resposta->fromCassandra( $column );

        return $resposta;
    }
}
