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
    protected $_nomeCF = 'usuario_compartilhamento';

    private $_nomeFeedCF = 'usuario_compartilhamento_feed';
    private $_nomeTimelineCF = 'usuario_compartilhamento_timeline';
    private $_FeedCF;
    private $_TimelineCF;

    private $_amizadeDAO;
    private $_usuarioDAO;
    private $_comentarioDAO;


    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_TimelineCF = $phpCassa->getColumnFamily($this->_nomeTimelineCF);
        $this->_FeedCF = $phpCassa->getColumnFamily($this->_nomeFeedCF);
        $this->_amizadeDAO = WeLearn_DAO_DAOFactory::create('AmizadeUsuarioDAO');
        $this->_usuarioDAO = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $feed=$this->_cf->get(CassandraUtil::import($id)->bytes);
        return $this->_criarFromCassandra($feed);
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

        $idFeeds = $this->_FeedCF->get($filtros['usuario']->getId(),null,$de,$ate,true,$filtros['count']);
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
        $feed = $this->recuperar($id);
        $uuidFeed = CassandraUtil::import($feed->getId())->bytes;
        $this->_comentarioDAO = WeLearn_DAO_DAOFactory::create('ComentarioFeedDAO');
        try{
            $amigosAtivos = $this->_amizadeDAO->recuperarTodosAmigosAtivos($feed->getCriador());
        }catch(cassandra_NotFoundException $e){
            $amigosAtivos = array();
        }

        try{
            $amigosInativos = $this->_amizadeDAO->recuperarTodosAmigosInativos($feed->getCriador());
        }catch(cassandra_NotFoundException $e){
            $amigosInativos = array();
        }

        $listaAmigos = array_merge($amigosAtivos,$amigosInativos);

        if(count($listaAmigos)>0){
            for($i=0; $i<count($listaAmigos);$i++)
            {
                $this->_FeedCF->remove($listaAmigos[$i],array($uuidFeed));
            }
        }
        $this->_cf->remove($uuidFeed);
        $this->_FeedCF->remove($feed->getCriador()->getId(),array($uuidFeed));
        $this->_comentarioDAO->removerTodosPorCompartilhamento($uuidFeed);


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
        $this->_FeedCF->insert($dto->getCriador()->getId(),array($UUID->bytes => ''));//inserindo na timeline do usuario criador do feed
        try{
           $amigosAtivos = $this->_amizadeDAO->recuperarTodosAmigosAtivos($dto->getCriador());
        }catch(cassandra_NotFoundException $e){
            $amigosAtivos = array();
        }

        if(count($amigosAtivos)>0){

           for($i=0; $i<count($amigosAtivos); $i++)
           {
               $this->_FeedCF->insert($amigosAtivos[$i],array($UUID->bytes => ''));
           }
        }
    }

    public function salvarTimeLine(WeLearn_DTO_IDTO &$dto, WeLearn_DTO_IDTO &$usuario)
    {
        if ($dto->isPersistido()) {
            $this->_atualizarTimeline($dto,$usuario);
        } else {
            $this->_adicionarTimeline($dto,$usuario);
        }
    }


    //so ira salvar no timeline cf do dono do perfil, no perfil da pessoa vao aparecer todos as publicaçoes que ela recebeu e ela poderá excluir todas
    //ao receber uma nova publicação no seu perfil sera gerada uma notificação para o dono do perfil, que ira direcionar para a nova notificação recebida
    protected function _adicionarTimeline(WeLearn_DTO_IDTO &$dto, WeLearn_DTO_IDTO &$usuario)
    {
        $UUID = UUID::mint();
        $dto->setId($UUID->string);
        $this->_cf->insert($UUID->bytes,$dto->toCassandra());
        $this->_TimelineCF->insert($usuario->getId(),array($UUID->bytes => ''));
    }

    public function removerTimeline(WeLearn_DTO_IDTO &$dto, WeLearn_DTO_IDTO &$usuario)
    {
        $this->_comentarioDAO = WeLearn_DAO_DAOFactory::create('ComentarioFeedDAO');
        $idFeed= CassandraUtil::import($dto->getId())->bytes;
        $this->_cf->remove($idFeed);
        $this->_TimelineCF->remove($usuario->getId(),array($idFeed));
        $this->_comentarioDAO->removerTodosPorCompartilhamento($idFeed);
    }


    public function recuperarTodosTimeline($de = '', $ate = '', array $filtros = null)
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
