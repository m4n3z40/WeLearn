<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 24/05/12
 * Time: 18:43
 * To change this template use File | Settings | File Templates.
 */
class ParticipacaoCursoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_participacao_aluno';

    /**
     * @var CertificadoDAO
     */
    private $_certificadoDao;

    /**
     * @var AvaliacaoDAO
     */
    private $_avaliacaoDao;

    /**
     * @var PaginaDAO
     */
    private $_paginaDao;

    /**
     * @var ControleModuloDAO
     */
    private $_controleModuloDao;

    /**
     * @var ControleAulaDAO
     */
    private $_controleAulaDao;

    /**
     * @var ControlePaginaDAO
     */
    private $_controlePaginaDao;

    function __construct()
    {
        $this->_certificadoDao = WeLearn_DAO_DAOFactory::create('CertificadoDAO');
        $this->_avaliacaoDao   = WeLearn_DAO_DAOFactory::create('AvaliacaoDAO');
        $this->_paginaDao      = WeLearn_DAO_DAOFactory::create('PaginaDAO');
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $noCurso
     * @return WeLearn_DTO_IDTO
     */
    public function inscrever(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $noCurso)
    {
        $participacao = $this->criarNovo(array(
            'aluno' => $aluno,
            'curso' => $noCurso,
            'situacao' => WeLearn_Cursos_SituacaoParticipacaoCurso::PARTICIPACAO_ATIVA,
            'dataInscricao' => time(),
            'dataUltimoAcesso' => time()
        ));

        $this->salvar( $participacao );

        return $participacao;
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function desvincular(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $doCurso)
    {
        $participacao = $this->recuperarPorCurso( $aluno, $doCurso );

        $participacao->setSituacao( WeLearn_Cursos_SituacaoParticipacaoCurso::INATIVO );

        $this->salvar( $participacao );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $cfKey = $dto->getCFKey();

        $this->_cf->insert( $cfKey, $dto->toCassandra() );

        $dto->setPersistido( true );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $cfKey = $dto->getCFKey();

        $this->_cf->insert( $cfKey, $dto->toCassandra() );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        return array();
    }

    /**
     * @param mixed $id
     * @return null|WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        if ( is_array( $id ) && isset( $id['aluno'] ) && isset( $id['curso'] ) ) {

            return $this->recuperarPorCurso( $id['aluno'], $id['curso'] );

        }

        return null;
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $curso
     * @return WeLearn_DTO_IDTO
     */
    public function recuperarPorCurso(WeLearn_Usuarios_Aluno $aluno,
                                      WeLearn_Cursos_Curso $curso)
    {
        $cfKey = WeLearn_Cursos_ParticipacaoCurso::gerarCFKey( $aluno, $curso );

        $column = $this->_cf->get( $cfKey );

        return $this->_criarFromCassadra( $column, $aluno, $curso );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        return 0;
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        if ( is_array( $id ) && isset( $id['aluno'] ) && isset( $id['curso'] ) ) {

            $this->removerPorCurso( $id['aluno'], $id['curso'] );

        }

        return null;
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $curso
     * @return WeLearn_DTO_IDTO
     */
    public function removerPorCurso(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $curso)
    {
        $participacao = $this->recuperarPorCurso( $aluno, $curso );

        $CFKey = WeLearn_Cursos_ParticipacaoCurso::gerarCFKey( $aluno, $curso );

        $this->_cf->remove( $CFKey );

        return $participacao;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_ParticipacaoCurso( $dados );
    }

    /**
     * @param array $column
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $curso
     * @return WeLearn_DTO_IDTO
     */
    private function _criarFromCassadra(array $column, WeLearn_Usuarios_Aluno $aluno,
                                        WeLearn_Cursos_Curso $curso)
    {
        if ( $column['paginaAtual'] ) {

            $column['paginaAtual'] = $this->_paginaDao->recuperar( $column['paginaAtual'] );

        } else { unset( $column['paginaAtual'] ); }

        if ( $column['avaliacaoAtual'] ) {

            $column['avaliacaoAtual'] = $this->_avaliacaoDao->recuperar( $column['avaliacaoAtual'] );

        } else { unset( $column['avaliacaoAtual'] ); }

        if ( $column['certificado'] ) {

            $column['certificado'] = $this->_certificadoDao->recuperar( $column['certificado'] );

        } else { unset( $column['certificado'] ); }

        $column['aluno'] = $aluno;
        $column['curso'] = $curso;

        $participacaoCurso = $this->criarNovo();
        $participacaoCurso->fromCassandra( $column );

        return $participacaoCurso;
    }

    /**
     * @return ControleModuloDAO
     */
    public function getControleModuloDAO()
    {
        if ( ! ($this->_controleModuloDao instanceof ControleModuloDAO) ) {

            $this->_controleModuloDao = WeLearn_DAO_DAOFactory::create(
                'ControleModuloDAO',
                null,
                false
            );

        }

        return $this->_controleModuloDao;
    }

    /**
     * @return ControleAulaDAO
     */
    public function getControleAulaDAO()
    {
        if ( ! ($this->_controleAulaDao instanceof ControleAulaDAO) ) {

            $this->_controleAulaDao = WeLearn_DAO_DAOFactory::create(
                'ControleAulaDAO',
                null,
                false
            );

        }

        return $this->_controleAulaDao;
    }

    /**
     * @return ControlePaginaDAO
     */
    public function getControlePaginaDAO()
    {

        if ( ! ($this->_controlePaginaDao instanceof ControlePaginaDAO) ) {

            $this->_controlePaginaDao = WeLearn_DAO_DAOFactory::create(
                'ControlePaginaDAO',
                null,
                false
            );

        }

        return $this->_controlePaginaDao;
    }
}
