<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 01/06/12
 * Time: 20:47
 * To change this template use File | Settings | File Templates.
 */
class ControleModuloDAO
{
    private $_nomeCF = 'cursos_participacao_aluno_modulo';

    /**
     * @var ColumnFamily|null
     */
    private $_cf;

    /**
     * @var ModuloDAO
     */
    private $_moduloDao;

    public function __construct()
    {
        $this->_cf = WL_Phpcassa::getInstance()->getColumnFamily(
            $this->_nomeCF
        );

        $this->_moduloDao = WeLearn_DAO_DAOFactory::create('ModuloDAO');
    }

    public function isDisponivel(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                 WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        try {

            $controleModulo = $this->recuperar( $modulo, $participacaoCurso );

            switch ( $controleModulo->getStatus() ) {
                case WeLearn_Cursos_Conteudo_StatusConteudo::ACESSANDO:
                case WeLearn_Cursos_Conteudo_StatusConteudo::FINALIZADO:
                    return true;
                case WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO:
                default:
                    return false;
            }

        } catch ( cassandra_NotFoundException $e ) {

            if ( $participacaoCurso->getModuloAtual()->getNroOrdem() === $modulo->getNroOrdem() ) {

                $this->acessar( $participacaoCurso, $modulo );

                return true;

            }

            return false;

        }
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function acessar(WeLearn_Cursos_ParticipacaoCurso &$participacaoCurso,
                            WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        try {

            $controleModulo = $this->recuperar( $modulo, $participacaoCurso );

        } catch ( cassandra_NotFoundException $e ) {

            $controleModulo = $this->criarNovo();

            $controleModulo->setParticipacaoCurso( $participacaoCurso );

            $controleModulo->setModulo( $modulo );

            $controleModulo->acessar();

            $this->salvar( $controleModulo );

        }

        $participacaoCurso->setModuloAtual( $modulo );
        WeLearn_DAO_DAOFactory::create('ParticipacaoCursoDAO')->salvar(
            $participacaoCurso
        );

        return $controleModulo;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function finalizar(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                              WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $controleModulo = $this->recuperar( $modulo, $participacaoCurso );

        if ( $controleModulo->getStatus() === WeLearn_Cursos_Conteudo_StatusConteudo::FINALIZADO ) {

            return $controleModulo;

        }

        $controleModulo->finalizar();

        $this->salvar( $controleModulo );

        return $controleModulo;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function bloquear(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                             WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $controleModulo = $this->recuperar( $modulo, $participacaoCurso );

        if ( $controleModulo->getStatus() === WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO ) {

            return $controleModulo;

        }

        $controleModulo->bloquear();

        $this->salvar( $controleModulo );

        return $controleModulo;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Modulo $modulo
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function recuperar(WeLearn_Cursos_Conteudo_Modulo $modulo,
                                            WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $moduloUUID = UUID::import( $modulo->getId() )->bytes;

        $column = $this->_cf->get(
            $participacaoCurso->getCFKey(),
            array( $moduloUUID )
        );

        return $this->_criarFromCassandra(
            $column,
            $participacaoCurso,
            $modulo
        );
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return array
     */
    public function recuperarTodos(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $count = ModuloDAO::MAX_MODULOS;

        $CFKey = $participacaoCurso->getCFKey();

        $columns = $this->_cf->get( $CFKey, null, '', '', false, $count );

        $controlesModulos = array();

        foreach ($columns as $column) {
            $controlesModulos[] = $this->_criarFromCassandra(
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
    public function recuperarQtdTotal(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        return $this->_cf->get_count( $participacaoCurso->getCFKey() );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_ControleModulo $controleModulo
     */
    public function salvar(WeLearn_Cursos_Conteudo_ControleModulo &$controleModulo)
    {
        $CFKey = $controleModulo->getParticipacaoCurso()->getCFKey();

        $this->_cf->insert( $CFKey, $controleModulo->toCassandra() );

        if ( ! $controleModulo->isPersistido() ) {

            $controleModulo->setPersistido( true );

        }
    }

    /**
     * @param array|null $dados
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_ControleModulo( $dados );
    }

    /**
     * @param array $column
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param null|WeLearn_Cursos_Conteudo_Modulo $modulo
     * @return WeLearn_Cursos_Conteudo_ControleModulo
     */
    private function _criarFromCassandra(
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

        $controleModulo = $this->criarNovo();
        $controleModulo->fromCassandra( $column );

        return $controleModulo;
    }
}
