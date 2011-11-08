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

    /**
     * @var SegmentoDAO
     */
    private $_segmentoDAO;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDAO;

    /**
     * @var ImagemCursoDAO
     */
    private $_imagemDAO;

    /**
     * @var ConfiguracaoCursoDAO
     */
    private $_configuracaoDAO;

    function __construct()
    {
        $phpCassa =& WL_Phpcassa::getInstance();

        $this->_cursosPorNomeCF = $phpCassa->getColumnFamily($this->_nomeCursosPorNomeCF);
        $this->_cursosPorAreaCF = $phpCassa->getColumnFamily($this->_nomeCUrsosPorAreaCF);
        $this->_cursosPorSegmentoCF = $phpCassa->getColumnFamily($this->_nomeCursosPorSegmentoCF);
        $this->_cursosPorCriadorCF = $phpCassa->getColumnFamily($this->_nomeCursosPorCriador);

        $this->_segmentoDAO = WeLearn_DAO_DAOFactory::create('SegmentoDAO');
        $this->_usuarioDAO = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_imagemDAO = WeLearn_DAO_DAOFactory::create('ImagemCursoDAO');
        $this->_configuracaoDAO = WeLearn_DAO_DAOFactory::create('ConfiguracaoCursoDAO');
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
        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        if ( ! is_null( $dto->getImagem() ) ) {
            $dto->getImagem()->setCursoId($dto->getId());
            $this->_imagemDAO->salvar($dto->getImagem());
        }

        if ( ! is_null( $dto->getConfiguracao() ) ) {
            $dto->getConfiguracao()->setCursoId($dto->getId());
            $this->_configuracaoDAO->salvar($dto->getConfiguracao());
        }

        //indexes

        //Retirando caracteres especiais do nome do curso
        $nomeSimplificado = url_title(convert_accented_characters(strtolower($dto->getNome())));
        $primeiraLetra = $nomeSimplificado[0];

        $this->_cursosPorNomeCF->insert($primeiraLetra, array($nomeSimplificado => $UUID->bytes));
        $this->_cursosPorAreaCF->insert('__todos__', array($UUID->bytes => ''));
        $this->_cursosPorAreaCF->insert($dto->getSegmento()->getArea()->getId(), array($UUID->bytes => ''));
        $this->_cursosPorSegmentoCF->insert($dto->getSegmento()->getId(), array($UUID->bytes => ''));
        $this->_cursosPorCriadorCF->insert($dto->getCriador()->getId(), array($UUID->bytes => ''));

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getId());

        //Verifica se houve alteração no segmento e reconstroi os indexes.
        $segmentoAtual = $this->_cf->get($UUID->bytes, array('segmento'));

        if ($segmentoAtual['segmento'] != $dto->getSegmento()->getId()) {
            $segmentoAtual = $this->_segmentoDAO->recuperar($segmentoAtual['segmento']);
            $this->_cursosPorAreaCF->remove($segmentoAtual->getArea()->getId(), array($UUID->bytes));
            $this->_cursosPorSegmentoCF->remove($segmentoAtual->getId(), array($UUID->bytes));

            $this->_cursosPorAreaCF->insert($dto->getSegmento()->getArea()->getId(), array($UUID->bytes => ''));
            $this->_cursosPorSegmentoCF->insert($dto->getSegmento()->getId(), array($UUID->bytes => ''));
        }

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        if ( ! is_null( $dto->getImagem() ) ) {
            $this->_imagemDAO->salvar($dto->getImagem());
        }

        if ( ! is_null( $dto->getConfiguracao() ) ) {
            $this->_configuracaoDAO->salvar($dto->getConfiguracao());
        }
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
        return $this->_configuracaoDAO->criarNovo($dados);
    }

    public function criarImagem(array $dados = null)
    {
        return $this->_imagemDAO->criarNovo($dados);
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_Segmento $segmentoPadrao = null,
                                         WeLearn_Usuarios_GerenciadorPrincipal $criadorPadrao = null)
    {
        $column['segmento'] = ($segmentoPadrao instanceof WeLearn_Cursos_Segmento)
                             ? $segmentoPadrao
                             : $this->_segmentoDAO->recuperar($column['segmento']);

        $column['criador'] = ($criadorPadrao instanceof WeLearn_Usuarios_GerenciadorPrincipal)
                            ? $criadorPadrao
                            : $this->_usuarioDAO->criarGerenciadorPrincipal(
                                $this->_usuarioDAO->recuperar($column['criador'])
                              );

        try{
            $column['imagem'] = $this->_imagemDAO->recuperar($column['id']);
        } catch (cassandra_NotFoundException $e) { }

        try {
            $column['configuracao'] = $this->_configuracaoDAO->recuperar($column['id']);
        } catch (cassandra_NotFoundException $e) { }

        $curso = new WeLearn_Cursos_Curso();
        $curso->fromCassandra($column);

        return $curso;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_Segmento $segmentoPadrao = null,
                                               WeLearn_Usuarios_GerenciadorPrincipal $criadorPadrao = null)
    {
        $listaCursosObjs = array();

        foreach ($columns as $column) {
            $this->_criarFromCassandra($column, $segmentoPadrao, $criadorPadrao);
        }

        return $listaCursosObjs;
    }
}
