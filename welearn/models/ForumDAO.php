<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 10/11/11
 * Time: 14:39
 * To change this template use File | Settings | File Templates.
 */
 
class ForumDAO extends WeLearn_DAO_AbstractDAO {

    protected $_nomeCF = 'cursos_forum';

    private $_nomeForumPorCategoriaCF = 'cursos_forum_por_categoria';
    private $_nomeForumAtivos = 'cursos_forum_ativos';
    private $_nomeForumInativos = 'cursos_forum_inativos';

    private $_forumPorCategoriaCF;
    private $_forumAtivosCF;
    private $_forumInativosCF;

    private $_categoriaDao;
    private $_usuarioDao;

    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_forumPorCategoriaCF = $phpCassa->getColumnFamily($this->_nomeForumPorCategoriaCF);
        $this->_forumAtivosCF = $phpCassa->getColumnFamily($this->_nomeForumAtivos);
        $this->_forumInativosCF = $phpCassa->getColumnFamily($this->_nomeForumInativos);

        $this->_categoriaDao = WeLearn_DAO_DAOFactory::create('CategoriaForumDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $categoriaUUID = CassandraUtil::import( $dto->getCategoria()->getId() );

        $dto->setId($UUID->string);
        $dto->setDataCriacao(time());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $this->_forumPorCategoriaCF->insert($categoriaUUID->bytes, array($UUID->bytes => ''));

        if ($dto->getStatus() == WeLearn_Cursos_Foruns_StatusForum::ATIVO) {
            $this->_forumAtivosCF->insert($categoriaUUID->bytes, array($UUID->bytes => ''));
        } else {
            $this->_forumInativosCF->insert($categoriaUUID->bytes, array($UUID->bytes => ''));
        }

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getid());
        $categoriaUUID = CassandraUtil::import($dto->getCategoria()->getId());

        $statusArray = $this->_cf->get($UUID->bytes, array('status'));
        $statusAntigo = (int)$statusArray['status'];

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        if ($statusAntigo != $dto->getStatus()) {
            if ($statusAntigo == WeLearn_Cursos_Foruns_StatusForum::ATIVO) {
                $this->_forumAtivosCF->remove($categoriaUUID->bytes, array($UUID->bytes));
                $this->_forumInativosCF->insert($categoriaUUID->bytes, array($UUID->bytes => ''));
            } else {
                $this->_forumInativosCF->remove($categoriaUUID->bytes, array($UUID->bytes));
                $this->_forumAtivosCF->insert($categoriaUUID->bytes, array($UUID->bytes => ''));
            }
        }
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if( ! isset($filtros['count']) ) {
            $count = 20;
        } else {
            $count = $filtros['count'];
        }

        if (isset($filtros['categoria']) &&
            isset($filtros['status']) &&
            ($filtros['categoria'] instanceof WeLearn_Cursos_Foruns_Categoria) ) {
            return $this->recuperarTodosPorCategoriaEStatus($filtros['categoria'], $filtros['status'], $de, $ate, $count);
        } elseif ( isset($filtros['categoria']) && ($filtros['categoria'] instanceof WeLearn_Cursos_Foruns_Categoria) ) {
            return $this->recuperarTodosPorCategoria($filtros['categoria'], $de, $ate, $count);
        }

        return array();
    }

    public function recuperarTodosPorCategoria(
        WeLearn_Cursos_Foruns_Categoria $categoria,
        $de = '',
        $ate = '',
        $count = 10)
    {
        if ( $de != '' && !($de instanceof UUID) ) {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ( $ate != '' && !($ate instanceof UUID) ) {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $categoriaUUID = CassandraUtil::import($categoria->getId());

        $idsForuns = array_keys(
                $this->_forumPorCategoriaCF->get($categoriaUUID->bytes,
                                                      null,
                                                      $de,
                                                      $ate,
                                                      true,
                                                      $count)
        );

        $columns = $this->_cf->multiget($idsForuns);

        return $this->_criarVariosFromCassandra($columns, $categoria);
    }

    public function recuperarTodosPorCategoriaEStatus(
        WeLearn_Cursos_Foruns_Categoria $categoria,
        $status = WeLearn_Cursos_Foruns_StatusForum::ATIVO,
        $de = '',
        $ate = '',
        $count = 10)
    {
        if ( $de != '' && !($de instanceof UUID) ) {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ( $ate != '' && !($ate instanceof UUID) ) {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $categoriaUUID = CassandraUtil::import($categoria->getId());

        if ($status == WeLearn_Cursos_Foruns_StatusForum::ATIVO) {
            $idsForuns = array_keys(
                $this->_forumAtivosCF->get($categoriaUUID->bytes, null, $de, $ate, true, $count)
            );
        } else {
            $idsForuns = array_keys(
                $this->_forumInativosCF->get($categoriaUUID->bytes, null, $de, $ate, true, $count)
            );
        }

        $columns = $this->_cf->multiget($idsForuns);

        return $this->_criarVariosFromCassandra($columns);
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
        if ( $de instanceof WeLearn_Cursos_Foruns_Categoria ) {
            return $this->recuperarQtdTotalPorCategoria($de);
        }

        return 0;
    }

    public function recuperarQtdTotalPorCategoria(WeLearn_Cursos_Foruns_Categoria $categoria)
    {
        $UUID = CassandraUtil::import($categoria->getId());

        return $this->_forumPorCategoriaCF->get_count($UUID->bytes);
    }

    public function recuperarQtdTotalPorCategoriaEStatus(WeLearn_Cursos_Foruns_Categoria $categoria,
                                                         $status = WeLearn_Cursos_Foruns_StatusForum::ATIVO)
    {
        $UUID = CassandraUtil::import($categoria->getId());

        if ($status == WeLearn_Cursos_Foruns_StatusForum::ATIVO) {
            return $this->_forumAtivosCF->get_count($UUID->bytes);
        } else {
            return $this->_forumInativosCF->get_count($UUID->bytes);
        }
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

        $forumRemovido = $this->recuperar($id);

        $categoriaUUID = CassandraUtil::import($forumRemovido->getCategoria()->getId());

        $this->_cf->remove($id->bytes);
        $this->_forumPorCategoriaCF->remove($categoriaUUID->bytes, array($id->bytes));

        if ($forumRemovido->getStatus() == WeLearn_Cursos_Foruns_StatusForum::ATIVO) {
            $this->_forumAtivosCF->remove($categoriaUUID->bytes, array($id->bytes));
        } else {
            $this->_forumInativosCF->remove($categoriaUUID->bytes, array($id->bytes));
        }

        WeLearn_DAO_DAOFactory::create('PostForumDAO')->removerTodosPorForum( $forumRemovido->getId() );

        $forumRemovido->setPersistido(false);

        return $forumRemovido;
    }

    /**
     * @param string $categoriaId
     * @return void
     */
    public function removerTodosPorCategoria($categoriaId)
    {
        $categoriaUUID = CassandraUtil::import($categoriaId);

        try {
            $idsForuns = array_keys(
                    $this->_forumPorCategoriaCF->get($categoriaUUID->bytes, null, '', '', true, 1000000)
            );

            $postDao = WeLearn_DAO_DAOFactory::create('PostForumDAO');
            foreach ($idsForuns as $id) {
                $this->_cf->remove($id);
                $postDao->removerTodosPorForum($id);
            }

            $this->_forumPorCategoriaCF->remove($categoriaUUID->bytes);
            $this->_forumAtivosCF->remove($categoriaUUID->bytes);
            $this->_forumInativosCF->remove($categoriaUUID->bytes);
        } catch (cassandra_NotFoundException $e) {
            return;
        }
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Foruns_Forum($dados);
    }

    private function _criarFromCassandra(array $column, WeLearn_Cursos_Foruns_Categoria $categoriaPadrao = null)
    {
        if ( $categoriaPadrao instanceof WeLearn_Cursos_Foruns_Categoria ) {
            $column['categoria'] = $categoriaPadrao;
        } else {
            $column['categoria'] = $this->_categoriaDao->recuperar($column['categoria']);
        }

        $column['criador'] = $this->_usuarioDao->recuperar($column['criador']);

        $forum = $this->criarNovo();
        $forum->fromCassandra($column);

        return $forum;
    }

    private function _criarVariosFromCassandra(array $columns, WeLearn_Cursos_Foruns_Categoria $categoriaPadrao = null)
    {
        $listaForuns = array();

        foreach ( $columns as $column) {
            $listaForuns[] = $this->_criarFromCassandra($column, $categoriaPadrao);
        }

        return $listaForuns;
    }
}
