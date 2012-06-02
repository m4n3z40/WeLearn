<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 01/06/12
 * Time: 20:47
 * To change this template use File | Settings | File Templates.
 */
class ControleAulaDAO
{
    private $_nomeCF = 'cursos_participacao_aluno_aula';

    /**
     * @var ColumnFamily|null
     */
    private $_cf;

    /**
     * @var AulaDAO
     */
    private $_aulaDao;

    public function __construct()
    {
        $this->_cf = WL_Phpcassa::getInstance()->getColumnFamily(
            $this->_nomeCF
        );

        $this->_aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function acessar(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $novoControleAula = $this->criarNovo();

        $novoControleAula->setParticipacaoCurso( $participacaoCurso );

        $novoControleAula->setAula( $aula );

        $novoControleAula->acessar();

        $this->salvar( $novoControleAula );

        return $novoControleAula;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function finalizar(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $controleAula = $this->recuperar( $aula, $participacaoCurso );

        $controleAula->finalizar();

        $this->salvar( $controleAula );

        return $controleAula;
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function bloquear(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $controleAula = $this->recuperar( $aula, $participacaoCurso );

        $controleAula->bloquear();

        $this->salvar( $controleAula );

        return $controleAula;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function recuperar(WeLearn_Cursos_Conteudo_Aula $aula,
                                          WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $CFKey = $participacaoCurso->getCFKey();
        $aulaUUID = UUID::import( $aula->getId() )->bytes;

        $column = $this->_cf->get( $CFKey, array( $aulaUUID ) );

        return $this->_criarFromCassandra(
            $column,
            $participacaoCurso,
            $aula
        );
    }

    /**
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @return array
     */
    public function recuperarTodos(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $count = AulaDAO::MAX_AULAS;

        $CFKey = $participacaoCurso->getCFKey();

        $columns = $this->_cf->get( $CFKey, null, '', '', false, $count );

        $controlesAula = array();

        foreach ($columns as $column) {
            $controlesAula[] = $this->_criarFromCassandra(
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
    public function recuperarQtdTotal(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        return $this->_cf->get_count( $participacaoCurso->getCFKey() );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_ControleAula $controleAula
     */
    public function salvar(WeLearn_Cursos_Conteudo_ControleAula &$controleAula)
    {
        $CFKey = $controleAula->getParticipacaoCurso()->getCFKey();

        $this->_cf->insert( $CFKey, $controleAula->toCassandra() );

        if ( ! $controleAula->isPersistido() ) {

            $controleAula->setPersistido( true );

        }
    }

    /**
     * @param array|null $dados
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_ControleAula( $dados );
    }

    /**
     * @param array $column
     * @param WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param null|WeLearn_Cursos_Conteudo_Aula $aula
     * @return WeLearn_Cursos_Conteudo_ControleAula
     */
    private function _criarFromCassandra(
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

        $controleAula = $this->criarNovo();
        $controleAula->fromCassandra( $column );

        return $controleAula;
    }
}
