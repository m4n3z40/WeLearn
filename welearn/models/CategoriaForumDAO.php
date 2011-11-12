<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 10:47
 * To change this template use File | Settings | File Templates.
 */
 
class CategoriaForumDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_forum_categorias';

    private $_nomeCategoriasPorCurso = 'cursos_forum_categorias_por_curso';

    private $_categoriasPorCursoCF;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_categoriasPorCursoCF = $phpCassa->getColumnFamily($this->_nomeCategoriasPorCurso);

        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
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
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        $count = 20;

        if (isset($filtros['count'])) {
            $count = $filtros['count'];
        }

        if (isset($filtros['curso']) && $filtros['curso'] instanceof WeLearn_Cursos_Curso) {
            return $this->recuperarTodosPorCurso($filtros['curso'], $de, $ate, $count);
        }

        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $columns = $this->_cf->get_range($de, $ate, $count);

        return $this->_criarVariosFromCassandra($columns);
    }

    public function recuperarTodosPorCurso(WeLearn_Cursos_Curso $curso, $de = '', $ate = '', $count = 20)
    {
        $cursoUUID = CassandraUtil::import($curso->getId());

        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $arrayKeys = array_keys(
            $this->_categoriasPorCursoCF->get($cursoUUID->bytes,
                                              null,
                                              $de,
                                              $ate,
                                              false,
                                              $count)
        );

        $columns = $this->_cf->multiget($arrayKeys);

        return $this->_criarVariosFromCassandra($columns, $curso);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Curso) {
            return $this->recuperarQtdTotalPorCurso($de);
        }

        return 0;
    }

    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = CassandraUtil::import($curso->getId());

        return $this->_categoriasPorCursoCF->get_count($cursoUUID->bytes);
    }

   
    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        if ( ! ( $id instanceof UUID ) ) {
            $id = CassandraUtil::import($id);
        }

        $categoriaRemovida = $this->recuperar($id);

        $cursoUUID = CassandraUtil::import($categoriaRemovida->getCurso()->getId());

        $this->_categoriasPorCursoCF->remove($cursoUUID->bytes, array($id->bytes));
        $this->_cf->remove($id->bytes);

        $categoriaRemovida->setPersistido(false);

        return $categoriaRemovida;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Foruns_Categoria($dados);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getId());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();

        $dto->setId($UUID->string);
        $dto->setDataCriacao(time());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $UUIDCurso = CassandraUtil::import($dto->getCurso()->getId());

        $this->_categoriasPorCursoCF->insert($UUIDCurso->bytes, array($UUID->bytes => ''));

        $dto->setPersistido(true);
    }

    /**
     * @param int $maxPag
     * @param int $iniPag
     * @param array $filtros
     * @return array
     */
    public function recuperarForuns($de = '', $ate = '', array $filtros = null)
    {
        $forumDao = WeLearn_DAO_DAOFactory::create('ForumDAO');

        return $forumDao->recuperarTodos($de, $ate, $filtros);
    }
    
    private function _criarFromCassandra(array $column, WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        $column['curso'] = ($cursoPadrao instanceof WeLearn_Cursos_Curso)
                            ? $cursoPadrao
                            : $this->_cursoDao->recuperar($column['curso']);

        try {
            $column['criador'] = $this->_usuarioDao->recuperar($column['criador']);
        } catch (cassandra_NotFoundException $e) {
            unset($column['criador']);
        }

        $categoria = new WeLearn_Cursos_Foruns_Categoria();
        $categoria->fromCassandra($column);

        return $categoria;
    }

    private function _criarVariosFromCassandra(array $columns, WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        $listaCategoriasObjs = array();
        foreach ($columns as $column) {
            $listaCategoriasObjs[] = $this->_criarFromCassandra($column, $cursoPadrao);
        }

        return $listaCategoriasObjs;
    }
}
