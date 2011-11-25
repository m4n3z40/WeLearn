<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 10/11/11
 * Time: 14:40
 * To change this template use File | Settings | File Templates.
 */
 
class PostForumDAO extends WeLearn_DAO_AbstractDAO {

    protected $_nomeCF = 'cursos_forum_posts';

    private $_nomePostsPorForumCF = 'cursos_forum_posts_por_forum';

    private $_postsPorForumCF;

    private $_forumDao;
    private $_usuarioDao;

    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_postsPorForumCF = $phpCassa->getColumnFamily($this->_nomePostsPorForumCF);

        $this->_forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $forumUUID = CassandraUtil::import($dto->getForum()->getId());

        $dto->setId($UUID->string);
        $dto->setDataCriacao(time());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $this->_postsPorForumCF->insert($forumUUID->bytes, array($UUID->bytes => ''));

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getId());

        $dto->setDataAlteracao(time());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if ( isset($filtros['count']) ) {
            $count = $filtros['count'];
        } else {
            $count = 10;
        }

        if ( isset($filtros['forum']) && ($filtros['forum'] instanceof WeLearn_Cursos_Foruns_Forum) ) {
            return $this->recuperarQtdTotalPorForum($filtros['forum'], $de, $ate, $count);
        }

        return array();
    }

    public function recuperarTodosPorForum(WeLearn_Cursos_Foruns_Forum $forum, $de = '', $ate = '', $count = 10)
    {
        $forumUUID = CassandraUtil::import($forum->getId());

        $idsPosts = array_keys(
                $this->_postsPorForumCF->get($forumUUID->bytes, null, $de, $ate, false, $count)
        );

        $columns = $this->_cf->multiget($idsPosts);

        return $this->_criarVariosFromCassandra($columns, $forum);
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
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ( $de instanceof WeLearn_Cursos_Foruns_Forum ) {
            return $this->recuperarQtdTotalPorForum($de);
        }

        return 0;
    }

    public function recuperarQtdTotalPorForum(WeLearn_Cursos_Foruns_Forum $forum)
    {
        $UUID = CassandraUtil::import($forum->getId());

        return $this->_postsPorForumCF->get_count($UUID->bytes);
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

        $post = $this->recuperar($id);

        $forumUUID = CassandraUtil::import($post->getForum()->getId());

        $this->_cf->remove($id->bytes);

        $this->_postsPorForumCF->remove($forumUUID->bytes, array($id->bytes));

        $post->setPersistido(false);

        return $post;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Foruns_Forum($dados);
    }

    public function _criarFromCassandra(array $column, WeLearn_Cursos_Foruns_Forum $forumPadrao = null)
    {
        if ( $forumPadrao instanceof WeLearn_Cursos_Foruns_Forum ) {
            $column['forum'] = $forumPadrao;
        } else {
            $column['forum'] = $this->_forumDao->recuperar($column['forum']);
        }

        $column['criador'] = $this->_usuarioDao->recuperar($column['criador']);

        $post = $this->criarNovo();

        $post->fromCassandra($column);

        return $post;
    }

    public function _criarVariosFromCassandra(array $columns, WeLearn_Cursos_Foruns_Forum $forumPadrao = null)
    {
        $listaPosts = array();

        foreach ( $columns as $column ) {
            $listaPosts[] = $this->_criarFromCassandra($column, $forumPadrao);
        }

        return $listaPosts;
    }
}
