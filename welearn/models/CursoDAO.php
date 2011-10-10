<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 11/08/11
 * Time: 19:35
 * To change this template use File | Settings | File Templates.
 */
 
class CursoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_curso';
    private $_nomeCursosPorNomeCF = 'cursos_curso_por_nome';
    private $_nomeCUrsosPorAreaCF = 'cursos_curso_por_area';
    private $_nomeCursosPorSegmentoCF = 'cursos_curso_por_segmento';
    private $_nomeCursosPorCriador = 'cursos_curso_por_criador';

    private $_cursosPorNomeCF;
    private $_cursosPorAreaCF;
    private $_cursosPorSegmentoCF;
    private $_cursosPorCriadorCF;

    private $_segmentoDAO;
    private $_usuarioDAO;

    function __construct()
    {
        $phpCassa =& WL_Phpcassa::getInstance();

        $this->_cursosPorNomeCF = $phpCassa->getColumnFamily($this->_nomeCursosPorNomeCF);
        $this->_cursosPorAreaCF = $phpCassa->getColumnFamily($this->_nomeCUrsosPorAreaCF);
        $this->_cursosPorSegmentoCF = $phpCassa->getColumnFamily($this->_nomeCursosPorSegmentoCF);
        $this->_cursosPorCriadorCF = $phpCassa->getColumnFamily($this->_nomeCursosPorCriador);

        $this->_segmentoDAO = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
        $this->_usuarioDAO = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implementar este metodo.
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
       return new WeLearn_Cursos_Curso($dados);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();

        $dto->setId($UUID->string);
        $dto->setDataCriacao(time());
        $dto->setStatus(WeLearn_Cursos_StatusCurso::CONTEUDO_BLOQUEADO);
        $dto->getImagem()->setCursoId($dto->getId());
        $dto->getConfiguracao()->setCursoId($dto->getId());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        //indexes

        //Retirando caracteres especiais do nome do curso
        $nomeSimplificado = url_title(convert_accented_characters(strtolower($dto->getNome())));
        $primeiraLetra = $nomeSimplificado[0];

        $this->_cursosPorNomeCF->insert($primeiraLetra, array($nomeSimplificado, $UUID->bytes));
        $this->_cursosPorAreaCF->insert($dto->getSegmento()->getArea()->getId(), array($UUID->bytes => ''));
        $this->_cursosPorSegmentoCF->insert($dto->getSegmento()->getId(), array($UUID->bytes => ''));
        $this->_cursosPorCriadorCF->insert($dto->getCriador()->getId(), array($UUID->bytes => ''));

        $dto->setPersistido(true);
        $dto->getImagem()->setPersistido(true);
        $dto->getConfiguracao()->setPersistido(true);
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
     * @param WeLearn_Cursos_Curso $curso
     * @return void
     */
    public function descontinuar(WeLearn_Cursos_Curso $curso)
    {
         // TODO: Implementar este metodo.
    }

    public function criarConfiguracao(array $dados = null)
    {
        return new WeLearn_Cursos_ConfiguracaoCurso($dados);
    }

    public function criarImagem(array $dados = null)
    {
        return new WeLearn_Cursos_ImagemCurso($dados);
    }
}
