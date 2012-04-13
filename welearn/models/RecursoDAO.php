<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 02/04/12
 * Time: 16:40
 * To change this template use File | Settings | File Templates.
 */
class RecursoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_recurso';

    private $_nomeRecursosGeraisCF = 'cursos_recurso_geral';
    private $_nomeRecursosRestritosCF = 'cursos_recurso_restrito';

    /**
     * @var ColumnFamily|null
     */
    private $_recursosGeraisCF;

    /**
     * @var ColumnFamily|null
     */
    private $_recursosRestritosCF;

    /**
     * @var AulaDAO
     */
    private $_aulaDao;

    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    /**
     * @var UsuarioDAO
     */

    private $_usuarioDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_recursosGeraisCF = $phpCassa->getColumnFamily(
            $this->_nomeRecursosGeraisCF
        );

        $this->_recursosRestritosCF = $phpCassa->getColumnFamily(
            $this->_nomeRecursosRestritosCF
        );

        $this->_aulaDao = WeLearn_DAO_DAOFactory::create('AulaDAO');
        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        if ( ! $dto->getId() ) {
            $UUID = UUID::mint();

            $dto->setId( $UUID->string );
        } else {
            $UUID = CassandraUtil::import( $dto->getId() );
        }

        $dto->setDataInclusao( time() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        if ( $dto instanceof WeLearn_Cursos_Recursos_RecursoRestrito ) {

            $aulaUUID = CassandraUtil::import( $dto->getAula()->getId() );

            $this->_recursosRestritosCF->insert(
                $aulaUUID->bytes,
                array($UUID->bytes => '')
            );
        } elseif ($dto instanceof WeLearn_Cursos_Recursos_RecursoGeral) {
            $cursoUUID = CassandraUtil::import( $dto->getCurso()->getId() );

            $this->_recursosGeraisCF->insert(
                $cursoUUID->bytes,
                array($UUID->bytes => '')
            );
        } else {
            throw new WeLearn_Base_Exception('A classe inserida tem que derivar
                                             de WeLearn_Cursos_Recursos_Recurso.');
        }

        $dto->setPersistido( true );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import( $dto->getId() );
        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if (isset($filtros['count'])) {
            $count = $filtros['count'];
        } else {
            $count = 10;
        }

        if (isset($filtros['curso']) &&
            $filtros['curso'] instanceof WeLearn_Cursos_Curso) {

            return $this->recuperarTodosGerais( $filtros['curso'], $de, $ate, $count );
        }

        if (isset($filtros['aula']) &&
            $filtros['aula'] instanceof WeLearn_Cursos_Conteudo_Aula) {

            return $this->recuperarTodosRestritos( $filtros['aula'], $de, $ate, $count );
        }

        return array();
    }

    public function recuperarTodosGerais(WeLearn_Cursos_Curso $curso,
                                         $de = '',
                                         $ate = '',
                                         $count = 10)
    {
        $cursoUUID = CassandraUtil::import( $curso->getId() );

        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsMRecursoos = array_keys(
            $this->_recursosGeraisCF->get($cursoUUID->bytes,
                                          null,
                                          $de,
                                          $ate,
                                          false,
                                          $count)
        );

        $columns = $this->_cf->multiget( $idsMRecursoos );

        return $this->_criarVariosFromCassandra( $columns, $curso );
    }

    public function recuperarTodosRestritos(WeLearn_Cursos_Conteudo_Aula $aula,
                                            $de = '',
                                            $ate = '',
                                            $count = 10)
    {
        $aulaUUID = CassandraUtil::import( $aula->getId() );

        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsRecursos = array_keys(
            $this->_recursosRestritosCF->get($aulaUUID->bytes,
                                             null,
                                             $de,
                                             $ate,
                                             false,
                                             $count)
        );

        $columns = $this->_cf->multiget( $idsRecursos );

        return $this->_criarVariosFromCassandra( $columns, $aula );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $UUID = CassandraUtil::import( $id );

        $column = $this->_cf->get( $UUID->bytes );

        return $this->_criarFromCassandra( $column );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ( $de instanceof WeLearn_Cursos_Curso ) {
            return $this->recuperarQtdTotalGerais( $de );
        } elseif ( $de instanceof WeLearn_Cursos_Conteudo_Aula ) {
            return $this->recuperarQtdTotalRestritoS( $de );
        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalGerais(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = CassandraUtil::import( $curso->getId() );

        return $this->_recursosGeraisCF->get_count( $cursoUUID->bytes );
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Aula $aula
     * @return int
     */
    public function recuperarQtdTotalRestritoS(WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $aulaUUID = CassandraUtil::import( $aula->getId() );

        return $this->_recursosRestritosCF->get_count( $aulaUUID->bytes );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $UUID = CassandraUtil::import( $id );

        $recursoRemovido = $this->recuperar($id);

        $this->_cf->remove( $UUID->bytes );

        if ($recursoRemovido instanceof WeLearn_Cursos_Recursos_RecursoRestrito) {
            $aulaUUID = CassandraUtil::import( $recursoRemovido->getAula()->getId() );

            $this->_recursosRestritosCF->remove($aulaUUID->bytes, array($UUID->bytes));
        } elseif ($recursoRemovido instanceof WeLearn_Cursos_Recursos_RecursoGeral) {
            $cursoUUID = CassandraUtil::import( $recursoRemovido->getCurso()->getId() );

            $this->_recursosGeraisCF->remove($cursoUUID->bytes, array($UUID->bytes));
        } else {
            throw new WeLearn_Base_Exception('A classe removida tem que derivar
                                             de WeLearn_Cursos_Recursos_Recurso.');
        }

        $recursoRemovido->setPersistido( false );

        return $recursoRemovido;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Recursos_Recurso($dados);
    }

    public function criarNovoGeral( array $dados = null )
    {
        return new WeLearn_Cursos_Recursos_RecursoGeral($dados);
    }

    public function criarNovoRestrito( array $dados = null )
    {
        return new WeLearn_Cursos_Recursos_RecursoRestrito($dados);
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_DTO_AbstractDTO $cursoOuAulaPadrao = null,
                                         WeLearn_Usuarios_Usuario $criadorPadrao = null)
    {
        if ($column['tipo'] == WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO) {
            $recurso = $this->criarNovoRestrito();

            if ($cursoOuAulaPadrao instanceof WeLearn_Cursos_Conteudo_Aula) {
                $column['aula'] = $cursoOuAulaPadrao;
            } else {
                $column['aula'] = $this->_aulaDao->recuperar( $column['aula'] );
            }
        } else {
            $recurso = $this->criarNovoGeral();

            if ($cursoOuAulaPadrao instanceof WeLearn_Cursos_Curso) {
                $column['curso'] = $cursoOuAulaPadrao;
            } else {
                $column['curso'] = $this->_cursoDao->recuperar( $column['curso'] );
            }
        }

        if ( $criadorPadrao instanceof WeLearn_Usuarios_Usuario ) {
            $column['criador'] = $criadorPadrao;
        } else {
            $column['criador'] = $this->_usuarioDao->recuperar( $column['criador'] );
        }

        $recurso->fromCassandra( $column );

        return $recurso;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_DTO_AbstractDTO $cursoOuAulaPadrao = null,
                                               WeLearn_Usuarios_Usuario $criadorPadrao = null)
    {
        $listaRecursos = array();

        foreach ($columns as $column) {
            $listaRecursos[] = $this->_criarFromCassandra(
                $column,
                $cursoOuAulaPadrao,
                $criadorPadrao
            );
        }

        return $listaRecursos;
    }
}
