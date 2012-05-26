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

    private $_nomeControleModuloCF = 'cursos_participacao_aluno_modulo';
    private $_nomeControleAulaCF = 'cursos_participacao_aluno_aula';
    private $_nomeControlePaginaCF = 'cursos_participacao_aluno_pagina';

    private $_controleModuloCF;
    private $_controleAulaCF;
    private $_controlePaginaCF;

    /**
     * @var PaginaDAO
     */
    private $_paginaDao;

    /**
     * @var PaginaDAO
     */
    private $_certificadoDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_controleModuloCF = $phpCassa->getColumnFamily(
            $this->_nomeControleModuloCF
        );

        $this->_controleAulaCF = $phpCassa->getColumnFamily(
            $this->_nomeControleAulaCF
        );

        $this->_controlePaginaCF = $phpCassa->getColumnFamily(
            $this->_nomeControlePaginaCF
        );
    }

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

    public function desvincular(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $doCurso)
    {
        $participacao = $this->recuperarPorCurso( $aluno, $doCurso );

        $participacao->setSituacao( WeLearn_Cursos_SituacaoParticipacaoCurso::INATIVO );

        $this->salvar( $participacao );
    }

    public function recuperarControleModulo(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $curso)
    {

    }

    public function salvarControleModulo(WeLearn_Cursos_Conteudo_ControleModulo $controleModulo)
    {

    }

    public function recuperarControleAula(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $curso)
    {

    }

    public function salvarControleAula(WeLearn_Cursos_Conteudo_ControleAula $controleAula)
    {

    }

    public function recuperarControlePagina(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $curso)
    {

    }

    public function salvarControlePagina(WeLearn_Cursos_Conteudo_ControlePagina $controlePagina)
    {

    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $cfKey = $this->getCFKey( $dto );

        $this->_cf->insert( $cfKey, $dto->toCassandra() );

        $dto->setPersistido( true );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $cfKey = $this->getCFKey( $dto );

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
        // TODO: Implement recuperarTodos() method.
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
        $cfKey = $this->gerarCFKey( $aluno, $curso );

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
        // TODO: Implement recuperarQtdTotal() method.
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

        $CFKey = $this->gerarCFKey( $aluno, $curso );

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
     * @param array|null $dados
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function criarNovoControleModulo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_ControleModulo( $dados );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function criarNovoControleAula(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_ControleAula( $dados );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function criarNovoControlePagina (array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_ControlePagina( $dados );
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return string
     */
    public function getCFKey(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        return $this->gerarCFKey(
            $participacaoCurso->getAluno(),
            $participacaoCurso->getCurso()
        );
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $curso
     * @return string
     */
    public function gerarCFKey(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $curso)
    {
        return $aluno->getId() . '::' . $curso->getId();
    }

    /**
     * @param $cfKey
     * @return array|bool
     */
    public function CFKeyToArray( $cfKey )
    {
        $explodedCfKey = explode('::', $cfKey);

        if ( count( $explodedCfKey ) == 2 ) {

            return array(
                'aluno' => $explodedCfKey[0],
                'curso' => $explodedCfKey[1]
            );

        }

        return false;
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

        if ( $column['certificado'] ) {

            $column['certificado'] = $this->_certificadoDao->recuperar( $column['certificado'] );

        } else { unset( $column['certificado'] ); }

        $column['aluno'] = $aluno;
        $column['curso'] = $curso;

        $participacaoCurso = $this->criarNovo();
        $participacaoCurso->fromCassandra( $column );

        return $participacaoCurso;
    }
}
