<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 01/06/12
 * Time: 20:47
 * To change this template use File | Settings | File Templates.
 */
class ControlePaginaDAO
{
    private $_nomeCF = 'cursos_participacao_aluno_pagina';

    private $_nomeContadorCF = 'contadores_utf8';
    private $_keyContador    = 'total_paginas_vistas';

    /**
     * @var ColumnFamily|null
     */
    private $_cf;

    /**
     * @var ColumnFamily|null
     */
    private $_contadorCF;

    /**
     * @var PaginaDAO
     */
    private $_paginaDao;

    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_cf         = $phpCassa->getColumnFamily( $this->_nomeCF );
        $this->_contadorCF = $phpCassa->getColumnFamily( $this->_nomeContadorCF );

        $this->_paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return bool
     */
    public function isDisponivel(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                 WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        try {

            $controlePagina = $this->recuperar( $pagina, $participacaoCurso );

            switch ( $controlePagina->getStatus() ) {

                case WeLearn_Cursos_Conteudo_StatusConteudo::ACESSANDO:
                case WeLearn_Cursos_Conteudo_StatusConteudo::ACESSADO:
                    return true;
                case WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO:
                default:
                    return false;
            }

        } catch (cassandra_NotFoundException $e) {

            if ( $participacaoCurso->getPaginaAtual()->getNroOrdem() === $pagina->getNroOrdem() ) {

                $this->acessar( $participacaoCurso, $pagina );

                return true;

            }

            return false;

        }
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function acessar(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                            WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        try {

            $controlePagina = $this->recuperar( $pagina, $participacaoCurso );

        } catch ( cassandra_NotFoundException $e ) {

            $controlePagina = $this->criarNovo();

            $controlePagina->setParticipacaoCurso( $participacaoCurso );

            $controlePagina->setPagina( $pagina );

            $controlePagina->acessar();

            $this->salvar( $controlePagina );

        }

        $participacaoCurso->setPaginaAtual( $pagina );
        $participacaoCurso->setTipoConteudoAtual( WeLearn_Cursos_Conteudo_TipoConteudo::PAGINA );
        WeLearn_DAO_DAOFactory::create('ParticipacaoCursoDAO')->salvar(
            $participacaoCurso
        );

        return $controlePagina;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param int $tempoVisualizacao
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function finalizar(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                    WeLearn_Cursos_Conteudo_Pagina $pagina,
                                    $tempoVisualizacao = 0)
    {
        $controlePagina = $this->recuperar( $pagina, $participacaoCurso );

        if ( $controlePagina->getStatus() === WeLearn_Cursos_Conteudo_StatusConteudo::ACESSADO ) {

            return $controlePagina;

        }

        $controlePagina->setTempoVisualizacao( $tempoVisualizacao );

        $controlePagina->finalizar();

        $this->salvar( $controlePagina );

        $this->incrementarPaginasVistas( $participacaoCurso->getCFKey() );

        return $controlePagina;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function bloquear(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                                  WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $controlePagina = $this->recuperar( $pagina, $participacaoCurso );

        if ( $controlePagina->getStatus() === WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO ) {

            return $controlePagina;

        }

        $controlePagina->bloquear();

        $this->salvar( $controlePagina );

        return $controlePagina;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function recuperar(WeLearn_Cursos_Conteudo_Pagina $pagina,
                              WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $paginaUUID = UUID::import( $pagina->getId() )->bytes;

        $column = $this->_cf->get(
            $participacaoCurso->getCFKey(),
            array( $paginaUUID )
        );

        return $this->_criarFromCassandra( $column, $participacaoCurso, $pagina );
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return array
     */
    public function recuperarTodos(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $count = PaginaDAO::MAX_PAGINAS;

        $CFKey = $participacaoCurso->getCFKey();

        $columns = $this->_cf->get( $CFKey, null, '', '', false, $count );

        $controlesPagina = array();

        foreach ($columns as $column) {
            $controlesPagina[] = $this->_criarFromCassandra(
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
    public function recuperarQtdTotal(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        return $this->_cf->get_count( $participacaoCurso->getCFKey() );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_ControlePagina $controlePagina
     */
    public function salvar(WeLearn_Cursos_Conteudo_ControlePagina &$controlePagina)
    {
        $CFKey = $controlePagina->getParticipacaoCurso()->getCFKey();

        $this->_cf->insert( $CFKey, $controlePagina->toCassandra() );

        if ( ! $controlePagina->isPersistido() ) {

            $controlePagina->setPersistido( true );

        }
    }

    /**
     * @param string $chaveParticipacaoCurso
     */
    public function incrementarPaginasVistas($chaveParticipacaoCurso)
    {
        $this->_contadorCF->add( $this->_keyContador, $chaveParticipacaoCurso );
    }

    /**
     * @param string $chaveParticipacaoCurso
     */
    public function decrementarPaginasVistas($chaveParticipacaoCurso)
    {
        $this->_contadorCF->add( $this->_keyContador, $chaveParticipacaoCurso, -1 );
    }

    /**
     * @param string $chaveParticipacaoCurso
     * @return int
     */
    public function recuperarQtdTotalPaginasVistas($chaveParticipacaoCurso)
    {
        try {

            $totalPaginasVistas = $this->_contadorCF->get(
                $this->_keyContador,
                array( $chaveParticipacaoCurso )
            );

            return $totalPaginasVistas[ $chaveParticipacaoCurso ];

        } catch ( cassandra_NotFoundException $e ) {

            return 0;

        }
    }

    /**
     * @param array|null $dados
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_ControlePagina( $dados );
    }

    /**
     * @param array $column
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param null|WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return WeLearn_Cursos_Conteudo_ControlePagina
     */
    private function _criarFromCassandra(
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

        $column['status'] = $column[ $paginaUUID ];

        $controlePagina = $this->criarNovo();
        $controlePagina->fromCassandra( $column );

        return $controlePagina;
    }
}
