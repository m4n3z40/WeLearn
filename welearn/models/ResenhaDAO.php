<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 06/05/12
 * Time: 16:47
 * To change this template use File | Settings | File Templates.
 */
class ResenhaDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_resenha';

    private $_nomeResenhaPorCursoCF = 'cursos_resenha_por_curso';
    private $_nomeAlunosQueEnviaramCF = 'cursos_resenha_alunos_que_avaliaram';

    private $_mysql_tbl_name = 'reviews';

    /**
     * @var ColumnFamily|null
     */
    private $_resenhaPorCursoCF;

    /**
     * @var ColumnFamily|null
     */
    private $_alunosQueEnviaramCF;

    /**
     * @var CursoDAO
     */
    private $_cursoDao;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    /**
     * @var RespostaResenhaDAO
     */
    private $_respostaDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();

        $this->_resenhaPorCursoCF = $phpCassa->getColumnFamily(
            $this->_nomeResenhaPorCursoCF
        );

        $this->_alunosQueEnviaramCF = $phpCassa->getColumnFamily(
            $this->_nomeAlunosQueEnviaramCF
        );

        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_respostaDao = WeLearn_DAO_DAOFactory::create('RespostaResenhaDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $cursoUUID = UUID::import( $dto->getCurso()->getId() );

        $dto->setId( $UUID->string );
        $dto->setDataEnvio( time() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        $this->_resenhaPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $UUID->bytes => '' )
        );

        $this->_alunosQueEnviaramCF->insert(
            $cursoUUID->bytes,
            array( $dto->getCriador()->getId() => $dto->getId() )
        );

        get_instance()->db->insert( $this->_mysql_tbl_name, $dto->toMySQL() );

        $dto->setPersistido( true );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = UUID::import( $dto->getId() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        if ( $dto->getResposta() instanceof WeLearn_Cursos_Reviews_RespostaResenha ) {
            $this->_respostaDao->salvar( $dto->getResposta() );
        }

        get_instance()->db->where( 'id', $dto->getId() )
                          ->update( $this->_mysql_tbl_name, $dto->toMySQL() );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if ( isset($filtros['count']) ) {
            $count = $filtros['count'];
        } else {
            $count = 10;
        }

        if ( isset($filtros['curso']) && $filtros['curso'] instanceof WeLearn_Cursos_Curso) {
            return $this->recuperarTodosPorCurso( $filtros['curso'], $de, $ate, $count );
        }

        return array();
    }


    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $curso
     * @return bool
     */
    public function usuarioJaEnviou(WeLearn_Usuarios_Usuario $usuario, WeLearn_Cursos_Curso $curso)
    {
        try {
            $cursoUUID = UUID::import( $curso->getId() );

            $this->_alunosQueEnviaramCF->get(
                $cursoUUID->bytes,
                array( $usuario->getId() )
            );

            return true;
        } catch(cassandra_NotFoundException $e) {
            return false;
        }
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorCurso(WeLearn_Cursos_Curso $curso,
                                           $de = '',
                                           $ate = '',
                                           $count = 10)
    {
        if ($de != '') {
            $de = UUID::import( $de )->bytes;
        }

        if ($ate != '') {
            $ate = UUID::import( $ate )->bytes;
        }

        $cursoUUID = UUID::import( $curso->getId() );

        $idsResenhas = array_keys(
            $this->_resenhaPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $idsResenhas );

        return $this->_criarVariosFromCassandra( $columns, $curso );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $UUID = UUID::import( $id );

        $column = $this->_cf->get( $UUID->bytes );

        return $this->_criarFromCassandra( $column );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return bool
     */
    public function recuperarReputacaoCurso(WeLearn_Cursos_Curso &$curso)
    {
        $res = get_instance()->db->select_avg('qualidade', 'mediaQualidade')
                             ->select_avg('dificuldade', 'mediaDificuldade')
                             ->where('curso_id', $curso->getId())
                             ->get($this->_mysql_tbl_name)
                             ->row();

        if ( $res ) {
            $curso->setMediaQualidade( $res->mediaQualidade );
            $curso->setMediaDificuldade( $res->mediaDificuldade );

            return true;
        }

        return false;
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Curso) {
            return $this->recuperarQtdTotalPorCurso( $de );
        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return int
     */
    public function recuperarQtdTotalPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        return $this->_resenhaPorCursoCF->get_count( $cursoUUID->bytes );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $resenhaRemovida = $this->recuperar( $id );

        $UUID = UUID::import($id);
        $cursoUUID = UUID::import( $resenhaRemovida->getCurso()->getId() );

        $this->_cf->remove($UUID->bytes);
        $this->_resenhaPorCursoCF->remove($cursoUUID->bytes, array($UUID->bytes));

        $this->_alunosQueEnviaramCF->remove(
            $cursoUUID->bytes,
            array( $resenhaRemovida->getCriador()->getId() )
        );

        if ($resenhaRemovida->getResposta() instanceof WeLearn_Cursos_Reviews_RespostaResenha) {
            $this->_respostaDao->remover( $resenhaRemovida->getId() );
        }

        get_instance()->db->delete($this->_mysql_tbl_name, array('id' => $id));

        $resenhaRemovida->setPersistido( false );

        return $resenhaRemovida;
    }

    /**
     * @param WeLearn_Cursos_Reviews_Resenha $resenha
     * @return WeLearn_DTO_IDTO|null
     */
    public function removerResposta(WeLearn_Cursos_Reviews_Resenha &$resenha)
    {
        if ( $resenha->getResposta() instanceof WeLearn_Cursos_Reviews_RespostaResenha ) {
            $resenha->removerResposta();

            $this->salvar( $resenha );

            return $this->_respostaDao->remover( $resenha->getId() );
        }

        return null;
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     */
    public function removerTodosPorCurso(WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso->getId() );

        $idsResenhas = array_keys(
            $this->_resenhaPorCursoCF->get(
                $cursoUUID->bytes,
                null,
                '',
                '',
                false,
                1000000
            )
        );

        foreach ($idsResenhas as $id) {
            $this->_cf->remove( $id );
            $this->_respostaDao->getCf()->remove( $id );
        }

        $this->_resenhaPorCursoCF->remove( $cursoUUID->bytes );

        $this->_alunosQueEnviaramCF->remove( $cursoUUID->bytes );

        get_instance()->db->delete(
            $this->_mysql_tbl_name,
            array('curso_id' => $curso->getId())
        );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Reviews_Resenha($dados);
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        $column['curso'] = ( $cursoPadrao instanceof WeLearn_Cursos_Curso )
                           ? $cursoPadrao
                           : $this->_cursoDao->recuperar( $column['curso'] );

        $column['criador'] = $this->_usuarioDao->recuperar( $column['criador'] );

        if ( $column['resposta'] ) {
            $column['resposta'] = $this->_respostaDao->recuperar( $column['resposta'] );
        } else {
            unset( $column['resposta'] );
        }

        $resenha = $this->criarNovo();
        $resenha->fromCassandra( $column );

        return $resenha;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_Curso $cursoPadrao = null)
    {
        $listaResenhas = array();

        foreach ($columns as $column) {
            $listaResenhas[] = $this->_criarFromCassandra( $column, $cursoPadrao );
        }

        return $listaResenhas;
    }
}
