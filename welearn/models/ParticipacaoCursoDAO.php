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

    /**
     * @var ColumnFamily|null
     */
    private $_controleModuloCF;

    /**
     * @var ColumnFamily|null
     */
    private $_controleAulaCF;

    /**
     * @var ColumnFamily|null
     */
    private $_controlePaginaCF;

    /**
     * @var PaginaDAO
     */
    private $_paginaDao;

    /**
     * @var AulaDAO
     */
    private $_aulaDao;

    /**
     * @var ModuloDAO
     */
    private $_moduloDao;

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

        $this->_paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
        $this->_aulaDao   = WeLearn_DAO_DAOFactory::create('AulaDAO');
        $this->_moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
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
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function acessarModulo(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                  WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $novoControleModulo = $this->criarNovoControleModulo();

        $novoControleModulo->setParticipacaoCurso( $participacaoCurso );

        $novoControleModulo->setModulo( $modulo );

        $novoControleModulo->acessar();

        $this->salvarControleModulo( $novoControleModulo );

        return $novoControleModulo;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function finalizarModulo(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                  WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $controleModulo = $this->recuperarControleModulo( $modulo, $participacaoCurso );

        $controleModulo->finalizar();

        $this->salvarControleModulo( $controleModulo );

        return $controleModulo;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function bloquearModulo(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                  WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $controleModulo = $this->recuperarControleModulo( $modulo, $participacaoCurso );

        $controleModulo->bloquear();

        $this->salvarControleModulo( $controleModulo );

        return $controleModulo;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function recuperarControleModulo(WeLearn_Cursos_Conteudo_Modulo $modulo,
                                            WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $CFKey = $this->getCFKey( $participacaoCurso );
        $moduloUUID = UUID::import( $modulo->getId() )->bytes;

        $column = $this->_controleModuloCF->get( $CFKey, array( $moduloUUID ) );

        return $this->_criarControleModuloFromCassandra(
            $column,
            $participacaoCurso,
            $modulo
        );
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return array
     */
    public function recuperarTodosControlesModulo(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $count = ModuloDAO::MAX_MODULOS;

        $CFKey = $this->getCFKey( $participacaoCurso );

        $columns = $this->_controleModuloCF->get( $CFKey, null, '', '', false, $count );

        $controlesModulos = array();

        foreach ($columns as $column) {
            $controlesModulos[] = $this->_criarControleModuloFromCassandra(
                $column,
                $participacaoCurso
            );
        }

        return $controlesModulos;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return int
     */
    public function recuperarQtdTotalControlesModulo(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $CFKey = $this->getCFKey( $participacaoCurso );

        return $this->_controleModuloCF->get_count( $CFKey );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_ControleModulo $controleModulo
     */
    public function salvarControleModulo(WeLearn_Cursos_Conteudo_ControleModulo &$controleModulo)
    {
        $CFKey = $this->getCFKey( $controleModulo->getParticipacaoCurso() );

        $this->_controleModuloCF->insert( $CFKey, $controleModulo->toCassandra() );

        if ( ! $controleModulo->isPersistido() ) {

            $controleModulo->setPersistido( true );

        }
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function acessarAula(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $novoControleAula = $this->criarNovoControleAula();

        $novoControleAula->setParticipacaoCurso( $participacaoCurso );

        $novoControleAula->setAula( $aula );

        $novoControleAula->acessar();

        $this->salvarControleAula( $novoControleAula );

        return $novoControleAula;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function finalizarAula(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $controleAula = $this->recuperarControleAula( $aula, $participacaoCurso );

        $controleAula->finalizar();

        $this->salvarControleAula( $controleAula );

        return $controleAula;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function bloquearAula(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $controleAula = $this->recuperarControleAula( $aula, $participacaoCurso );

        $controleAula->bloquear();

        $this->salvarControleAula( $controleAula );

        return $controleAula;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function recuperarControleAula(WeLearn_Cursos_Conteudo_Aula $aula,
                                          WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $CFKey = $this->getCFKey( $participacaoCurso );
        $aulaUUID = UUID::import( $aula->getId() )->bytes;

        $column = $this->_controleAulaCF->get( $CFKey, array( $aulaUUID ) );

        return $this->_criarControleAulaFromCassandra(
            $column,
            $participacaoCurso,
            $aula
        );
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return array
     */
    public function recuperarTodosControlesAula(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $count = AulaDAO::MAX_AULAS;

        $CFKey = $this->getCFKey( $participacaoCurso );

        $columns = $this->_controleAulaCF->get( $CFKey, null, '', '', false, $count );

        $controlesAula = array();

        foreach ($columns as $column) {
            $controlesAula[] = $this->_criarControleAulaFromCassandra(
                $column,
                $participacaoCurso
            );
        }

        return $controlesAula;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return int
     */
    public function recuperarQtdTotalControlesAula(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $CFKey = $this->getCFKey( $participacaoCurso );

        return $this->_controleAulaCF->get_count( $CFKey );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_ControleAula $controleAula
     */
    public function salvarControleAula(WeLearn_Cursos_Conteudo_ControleAula &$controleAula)
    {
        $CFKey = $this->getCFKey( $controleAula->getParticipacaoCurso() );

        $this->_controleAulaCF->insert( $CFKey, $controleAula->toCassandra() );

        if ( ! $controleAula->isPersistido() ) {

            $controleAula->setPersistido( true );

        }
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function acessarPagina(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                  WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $novoControlePagina = $this->criarNovoControlePagina();

        $novoControlePagina->setParticipacaoCurso( $participacaoCurso );

        $novoControlePagina->setPagina( $pagina );

        $novoControlePagina->acessar();

        $this->salvarControlePagina( $novoControlePagina );

        $participacaoCurso->setPaginaAtual( $pagina );

        $this->salvar( $participacaoCurso );

        return $novoControlePagina;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param float $tempoVisualizacao
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function finalizarPagina(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                    WeLearn_Cursos_Conteudo_Pagina $pagina,
                                    $tempoVisualizacao = 0)
    {
        $controlePagina = $this->recuperarControlePagina( $pagina, $participacaoCurso );

        $controlePagina->setTempoVisualizacao( $tempoVisualizacao );

        $controlePagina->finalizar();

        $this->salvarControlePagina( $controlePagina );

        return $controlePagina;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function bloquearPagina(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                  WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $controlePagina = $this->recuperarControlePagina( $pagina, $participacaoCurso );

        $controlePagina->bloquear();

        $this->salvarControlePagina( $controlePagina );

        return $controlePagina;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function recuperarControlePagina(WeLearn_Cursos_Conteudo_Pagina $pagina,
                                            WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $CFKey = $this->getCFKey( $participacaoCurso );
        $paginaUUID = UUID::import( $pagina->getId() )->bytes;

        $column = $this->_controlePaginaCF->get( $CFKey, array( $paginaUUID ) );

        return $this->_criarControlePaginaFromCassandra( $column, $participacaoCurso, $pagina );
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return array
     */
    public function recuperarTodosControlesPagina(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $count = PaginaDAO::MAX_PAGINAS;

        $CFKey = $this->getCFKey( $participacaoCurso );

        $columns = $this->_controlePaginaCF->get( $CFKey, null, '', '', false, $count );

        $controlesPagina = array();

        foreach ($columns as $column) {
            $controlesPagina[] = $this->_criarControlePaginaFromCassandra(
                $column,
                $participacaoCurso
            );
        }

        return $controlesPagina;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return int
     */
    public function recuperarQtdTotalControlesPagina(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $CFKey = $this->getCFKey( $participacaoCurso );

        return $this->_controlePaginaCF->get_count( $CFKey );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_ControlePagina $controlePagina
     */
    public function salvarControlePagina(WeLearn_Cursos_Conteudo_ControlePagina &$controlePagina)
    {
        $CFKey = $this->getCFKey( $controlePagina->getParticipacaoCurso() );

        $this->_controlePaginaCF->insert( $CFKey, $controlePagina->toCassandra() );

        if ( ! $controlePagina->isPersistido() ) {

            $controlePagina->setPersistido( true );

        }
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

    /**
     * @param array $column
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param null|WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    private function _criarControleModuloFromCassandra(
            array $column,
            WeLearn_Cursos_ParticipacaoCurso
            $participacaoCurso,
            WeLearn_Cursos_Conteudo_Modulo $modulo = null
    ) {
        $moduloUUID = key( $column );

        $column['modulo'] = ( $modulo instanceof WeLearn_Cursos_Conteudo_Modulo )
                            ? $modulo
                            : $this->_moduloDao->recuperar( $moduloUUID );

        $column['participacaoCurso'] = $participacaoCurso;

        $column['status'] = $column[ $moduloUUID ];

        $controleModulo = $this->criarNovoControleModulo();
        $controleModulo->fromCassandra( $column );

        return $controleModulo;
    }

    /**
     * @param array $column
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param null|WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    private function _criarControleAulaFromCassandra(
            array $column,
            WeLearn_Cursos_ParticipacaoCurso
            $participacaoCurso,
            WeLearn_Cursos_Conteudo_Aula $aula = null
    ) {
        $aulaUUID = key( $column );

        $column['aula'] = ( $aula instanceof WeLearn_Cursos_Conteudo_Aula )
                          ? $aula
                          : $this->_aulaDao->recuperar( $aulaUUID );

        $column['participacaoCurso'] = $participacaoCurso;

        $column['status'] = $column[ $aulaUUID ];

        $controleAula = $this->criarNovoControleAula();
        $controleAula->fromCassandra( $column );

        return $controleAula;
    }

    /**
     * @param array $column
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param null|WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    private function _criarControlePaginaFromCassandra(
            array $column,
            WeLearn_Cursos_ParticipacaoCurso
            $participacaoCurso,
            WeLearn_Cursos_Conteudo_Pagina $pagina = null
    ) {
        $paginaUUID = key( $column );

        $column['pagina'] = ( $pagina instanceof WeLearn_Cursos_Conteudo_Pagina )
                            ? $pagina
                            : $this->_paginaDao->recuperar( $paginaUUID );

        $column['participacaoCurso'] = $participacaoCurso;

        $arrDetalhes = explode( '|', $column[ $paginaUUID ] );

        $column['status'] = $arrDetalhes[0];

        $column['tempoVisualizacao'] = $arrDetalhes[1];

        $controlePagina = $this->criarNovoControlePagina();
        $controlePagina->fromCassandra( $column );

        return $controlePagina;
    }
}
