<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 03/06/12
 * Time: 14:54
 * To change this template use File | Settings | File Templates.
 */
class ComentarioFeedDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'usuario_compartilhamento_comentario';
    private $_nomeComentarioPorCompartilhamentoCF = 'usuario_comentario_por_compartilhamento';
    private $_comentarioPorCompartilhamentoCF;

    private $_usuarioDAO;
    private $_feedDAO;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_cf = $phpCassa->getColumnFamily($this->_nomeCF);
        $this->_comentarioPorCompartilhamentoCF = $phpCassa->getColumnFamily($this->_nomeComentarioPorCompartilhamentoCF);
        $this->_usuarioDAO = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_feedDAO = WeLearn_DAO_DAOFactory::create('FeedDAO');
    }
    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
       $UUID = UUID::mint();
       $dto->setId($UUID->string);
       $this->_cf->insert($UUID->bytes,$dto->toCassandra());
       $uuidFeed = CassandraUtil::import($dto->getCompartilhamento()->getId())->bytes;
       $this->_comentarioPorCompartilhamentoCF->insert($uuidFeed,array($UUID->bytes => ''));
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implement _atualizar() method.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
       $idFeed = CassandraUtil::import($filtros['idFeed'])->bytes;
       $idComentarios = array_keys($this->_comentarioPorCompartilhamentoCF->get($idFeed));
       $comentarios = $this->_cf->multiget($idComentarios);
       return $this->_criarVariosFromCassandra($comentarios);
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implement recuperar() method.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implement recuperarQtdTotal() method.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        // TODO: Implement remover() method.
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Compartilhamento_ComentarioFeed();
    }

    private function _criarFromCassandra(array $column)
    {
        $column['criador'] = $this->_usuarioDAO->recuperar($column['criador']);
        $column['compartilhamento'] = $this->_feedDAO->recuperar($column['compartilhamento']);
        $feed = $this->criarNovo();
        $feed->fromCassandra($column);
        return $feed;
    }

    private function _criarVariosFromCassandra(array $columns)
    {
        $arrayFeeds = array();

        foreach ( $columns as $column ) {
            $arrayFeeds[] = $this->_criarFromCassandra($column);
        }

        return $arrayFeeds;
    }
}
