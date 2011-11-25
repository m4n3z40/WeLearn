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
    private $_nomeForumPorCategoriaEStatusSuperCF = 'cursos_forum_por_status_e_categoria';

    private $_forumPorCategoriaCF;
    private $_forumPorCategoriaEStatusSuperCF;

    private $_categoriaDao;
    private $_usuarioDao;

    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_forumPorCategoriaCF = $phpCassa->getColumnFamily($this->_nomeForumPorCategoriaCF);
        $this->_forumPorCategoriaEStatusSuperCF = $phpCassa->getColumnFamily($this->_nomeForumPorCategoriaEStatusSuperCF);

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
        $this->_forumPorCategoriaEStatusSuperCF->insert(
            $categoriaUUID->bytes,
            array(
                 $dto->getStatus() => array(
                     $UUID->bytes => ''
                 )
            )
        );

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

        if ( $statusAntigo != $dto->getStatus() ) {
            $this->_forumPorCategoriaEStatusSuperCF->remove($categoriaUUID->bytes, array($UUID->bytes), $statusAntigo);

            $this->_forumPorCategoriaEStatusSuperCF->insert(
                $categoriaUUID->bytes,
                array(
                     $dto->getStatus() => array(
                         $UUID->bytes => ''
                     )
                )
            );
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
        $count = 20)
    {
        if ( ! ($de instanceof UUID) ) {
            $de = CassandraUtil::import($de);
        }

        if ( ! ($ate instanceof UUID) ) {
            $ate = CassandraUtil::import($ate);
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
        $count = 20)
    {
        if ( ! ($de instanceof UUID) ) {
            $de = CassandraUtil::import($de);
        }

        if ( ! ($ate instanceof UUID) ) {
            $ate = CassandraUtil::import($ate);
        }

        $categoriaUUID = CassandraUtil::import($categoria->getId());

        $idsForuns = array_keys(
                $this->_forumPorCategoriaEStatusSuperCF->get($categoriaUUID->bytes,
                                                                  null,
                                                                  $de,
                                                                  $ate,
                                                                  true,
                                                                  $count,
                                                                  $status)
        );

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
        $this->_forumPorCategoriaEStatusSuperCF->remove($categoriaUUID->bytes, array($id->bytes), $forumRemovido->getStatus());

        $forumRemovido->setPersistido(false);

        return $forumRemovido;
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
            $column['categoria'] = $this->_categoriaDao->recuperar($column['criador']);
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
