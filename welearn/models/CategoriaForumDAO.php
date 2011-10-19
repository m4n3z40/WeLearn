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
        // TODO: implementar este metodo.
    }


    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implementar este metodo.
    }

   
    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        // TODO: Implementar este metodo.
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
        // TODO: Implementar este metodo.
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
     * @return void
     */
    public function recuperarForuns(int $maxPag, int $iniPag, Array $filtros )
    {
        // TODO: Implementar este metodo.
    }
    

}
