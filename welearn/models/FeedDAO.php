<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 10:39
 * To change this template use File | Settings | File Templates.
 */
 
class FeedDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'compartilhamento_feed';

    private   $_nomeTimelineCF = 'compartilhamento_usuario_timeline';
    private $_TimelineCF;
    private $_amizadeDAO;
    private $_usuarioDAO;


    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_TimelineCF = $phpCassa->getColumnFamily($this->_nomeTimelineCF);
        $this->_amizadeDAO = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $this->_usuarioDAO = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
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
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idFeeds = $this->_TimelineCF->get($filtros['usuario']->getId(),null,$de,$ate,true,$filtros['count']);
        $resultado = $this->_cf->multiget(array_keys($idFeeds));
        return $this->_criarVariosFromCassandra($resultado);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
         // TODO: Implementar este metodo
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        // TODO: Implementar este metodo
    }

     /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Compartilhamento_Feed();
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implementar este metodo
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $dto->setId($UUID->string);
        $this->_cf->insert($UUID->bytes,$dto->toCassandra());
        $this->_TimelineCF->insert($dto->getCriador()->getId(),array($UUID->bytes => ''));//inserindo na timeline do usuario criador do feed
        $totalAmigos = $this->_amizadeDAO->recuperarQtdTotalAmigos($dto->getCriador());// verifica se o usuario possui algum amigo
        if($totalAmigos!=0){
            $amigos=$this->_amizadeDAO->recuperarTodosAmigos($dto->getCriador());//recuperando amigos do usuario
            foreach ($amigos as $row) {
                $this->_TimelineCF->insert($row->getId(),array($UUID->bytes => ''));// inserindo na timeline dos amigos do criador
            }
        }
    }

    public function salvarPerfil(WeLearn_DTO_IDTO &$feed, WeLearn_Usuarios_Usuario $usuarioPerfil)
    {

    }

    protected function _adicionarPerfil()
    {

    }

    protected function _atualizarPerfil()
    {

    }

    private function _criarFromCassandra(array $column)
    {
        $column['criador']=$this->_usuarioDAO->recuperar($column['criador']);
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
