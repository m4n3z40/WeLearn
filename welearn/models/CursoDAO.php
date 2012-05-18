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
    private $_nomeCursosPorAlunoCF = 'cursos_curso_por_aluno';
    private $_nomeCursosPorInscricoesCF = 'cursos_curso_por_inscricao';
    private $_nomeCursosPorGerenciadoresCF = 'cursos_curso_por_gerenciador';
    private $_nomeCursosPorConviteGerenciadorCF = 'cursos_curso_por_convite_gerenciador';

    private $_nomeUsuariosPorCursoCF = 'cursos_usuario_por_curso';

    private $_cursosPorNomeCF;
    private $_cursosPorAreaCF;
    private $_cursosPorSegmentoCF;
    private $_cursosPorCriadorCF;
    private $_cursosPorAlunoCF;
    private $_cursosPorInscricoesCF;
    private $_cursosPorGerenciadoresCF;
    private $_cursosPorConviteGerenciadorCF;

    private $_usuariosPorCursoCF;

    private $_mysql_tbl_name = 'cursos';

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
        $this->_cursosPorAlunoCF = $phpCassa->getColumnFamily($this->_nomeCursosPorAlunoCF);
        $this->_cursosPorInscricoesCF = $phpCassa->getColumnFamily($this->_nomeCursosPorInscricoesCF);
        $this->_cursosPorGerenciadoresCF = $phpCassa->getColumnFamily($this->_nomeCursosPorGerenciadoresCF);
        $this->_cursosPorConviteGerenciadorCF = $phpCassa->getColumnFamily($this->_nomeCursosPorConviteGerenciadorCF);

        $this->_usuariosPorCursoCF = $phpCassa->getColumnFamily($this->_nomeUsuariosPorCursoCF);

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
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     * @return int
     */
    public function recuperarTipoDeVinculo(WeLearn_Usuarios_Usuario $usuario, WeLearn_Cursos_Curso $noCurso)
    {
        try {
            $cursoUUID = UUID::import( $noCurso->getId() );

            $column = $this->_usuariosPorCursoCF->get(
                $cursoUUID->bytes,
                array( $usuario->getId() )
            );

            return (int)$column[ $usuario->getId() ];
        } catch ( cassandra_NotFoundException $e ) {
            return WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO;
        }
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
     * @param WeLearn_Cursos_Area $area
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorArea(WeLearn_Cursos_Area $area,
                                          $de = '',
                                          $ate = '',
                                          $count = 20)
    {
        if ( $de != '' ) {
            $de = UUID::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_cursosPorAreaCF->get(
                $area->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param WeLearn_Cursos_Segmento $segmento
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorSegmento(WeLearn_Cursos_Segmento $segmento,
                                              $de = '',
                                              $ate = '',
                                              $count = 20)
    {
        if ( $de != '' ) {
            $de = UUID::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_cursosPorSegmentoCF->get(
                $segmento->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns, $segmento );
    }

    /**
     * @param WeLearn_Usuarios_GerenciadorPrincipal $criador
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorCriador(WeLearn_Usuarios_GerenciadorPrincipal $criador,
                                             $de = '',
                                             $ate = '',
                                             $count = 20)
    {
        if ( $de != '' ) {
            $de = UUID::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_cursosPorCriadorCF->get(
                $criador->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns, null, $criador );
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorAluno(WeLearn_Usuarios_Aluno $aluno,
                                           $de = '',
                                           $ate = '',
                                           $count = 20)
    {
        if ( $de != '' ) {
            $de = UUID::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_cursosPorAlunoCF->get(
                $aluno->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorInscricao(WeLearn_Usuarios_Usuario $usuario,
                                               $de = '',
                                               $ate = '',
                                               $count = 20)
    {
        if ( $de != '' ) {
            $de = UUID::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_cursosPorInscricoesCF->get(
                $usuario->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorGerenciador(WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador,
                                                 $de = '',
                                                 $ate = '',
                                                 $count = 20)
    {
        if ( $de != '' ) {
            $de = UUID::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_cursosPorGerenciadoresCF->get(
                $gerenciador->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorConviteGerenciador(WeLearn_Usuarios_Usuario $usuario,
                                                        $de = '',
                                                        $ate = '',
                                                        $count = 20)
    {
        if ( $de != '' ) {
            $de = UUID::import( $de )->bytes;
        }

        if ( $ate != '' ) {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_cursosPorConviteGerenciadorCF->get(
                $usuario->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
       return $this->_cursosPorAreaCF->get_count('__todos__');
    }

    /**
     * @param WeLearn_Cursos_Area $area
     * @return int
     */
    public function recuperarQtdTotalPorArea(WeLearn_Cursos_Area $area)
    {
        return $this->_cursosPorAreaCF->get_count( $area->getId() );
    }

    /**
     * @param WeLearn_Cursos_Segmento $segmento
     * @return int
     */
    public function recuperarQtdTotalPorSegmento(WeLearn_Cursos_Segmento $segmento)
    {
        return $this->_cursosPorSegmentoCF->get_count( $segmento->getId() );
    }

    /**
     * @param WeLearn_Usuarios_GerenciadorPrincipal $criador
     * @return int
     */
    public function recuperarQtdTotalPorCriador(WeLearn_Usuarios_GerenciadorPrincipal $criador)
    {
        return $this->_cursosPorCriadorCF->get_count( $criador->getId() );
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @return int
     */
    public function recuperarQtdTotalPorAluno(WeLearn_Usuarios_Aluno $aluno)
    {
        return $this->_cursosPorAlunoCF->get_count( $aluno->getId() );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return int
     */
    public function recuperarQtdTotalPorInscricao(WeLearn_Usuarios_Usuario $usuario)
    {
        return $this->_cursosPorInscricoesCF->get_count( $usuario->getId() );
    }

    /**
     * @param WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador
     * @return int
     */
    public function recuperarQtdTotalPorGerenciador(WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador)
    {
        return $this->_cursosPorGerenciadoresCF->get_count( $gerenciador->getId() );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return int
     */
    public function recuperarQtdTotalPorConviteGerenciador(WeLearn_Usuarios_Usuario $usuario)
    {
        return $this->_cursosPorConviteGerenciadorCF->get_count( $usuario->getId() );
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $noCurso
     */
    public function salvarAluno(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_usuariosPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $aluno->getId() => $aluno->getNivelAcesso() )
        );

        $this->_cursosPorAlunoCF->insert(
            $aluno->getId(),
            array( $cursoUUID->bytes => '' )
        );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     */
    public function salvarInscricao(WeLearn_Usuarios_Usuario $usuario, WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_usuariosPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $usuario->getId() => WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO_INSCRICAO_PENDENTE )
        );

        $this->_cursosPorInscricoesCF->insert(
            $usuario->getId(),
            array( $cursoUUID->bytes => '' )
        );
    }

    /**
     * @param WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador
     * @param WeLearn_Cursos_Curso $noCurso
     */
    public function salvarGerenciador(WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador,
                                      WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_usuariosPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $gerenciador->getId() => $gerenciador->getNivelAcesso() )
        );

        $this->_cursosPorGerenciadoresCF->insert(
            $gerenciador->getId(),
            array( $cursoUUID->bytes => '' )
        );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $noCurso
     */
    public function salvarConviteGerenciador(WeLearn_Usuarios_Usuario $usuario,
                                             WeLearn_Cursos_Curso $noCurso)
    {
        $cursoUUID = UUID::import( $noCurso->getId() );

        $this->_usuariosPorCursoCF->insert(
            $cursoUUID->bytes,
            array( $usuario->getId() => WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_CONVITE_PENDENTE )
        );

        $this->_cursosPorConviteGerenciadorCF->insert(
            $usuario->getId(),
            array( $cursoUUID->bytes => '' )
        );
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function removerAluno(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::import( $doCurso->getId() );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $aluno->getId() )
        );

        $this->_cursosPorAlunoCF->remove(
            $aluno->getId(),
            array( $cursoUUID->bytes )
        );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function removerInscricao(WeLearn_Usuarios_Usuario $usuario, WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::import( $doCurso->getId() );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $usuario->getId() )
        );

        $this->_cursosPorInscricoesCF->remove(
            $usuario->getId(),
            array( $cursoUUID->bytes )
        );
    }

    /**
     * @param WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function removerGerenciador(WeLearn_Usuarios_GerenciadorAuxiliar $gerenciador,
                                       WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::import( $doCurso->getId() );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $gerenciador->getId() )
        );

        $this->_cursosPorGerenciadoresCF->remove(
            $gerenciador->getId(),
            array( $cursoUUID->bytes )
        );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function removerConviteGerenciador(WeLearn_Usuarios_Usuario $usuario,
                                              WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::import( $doCurso->getId() );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $usuario->getId() )
        );

        $this->_cursosPorConviteGerenciadorCF->remove(
            $usuario->getId(),
            array( $cursoUUID->bytes )
        );
    }

    /**
     * @param array $idsAlunos
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function removerTodosAlunos(array $idsAlunos, WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::mint( $doCurso->getId() );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $idsAlunos )
        );

        foreach ($idsAlunos as $id) {

            $this->_cursosPorAlunoCF->remove(
                $id,
                array( $cursoUUID->bytes )
            );

        }
    }

    /**
     * @param array $idsUsuarios
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function removerTodasInscricoes(array $idsUsuarios, WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::mint( $doCurso->getId() );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $idsUsuarios )
        );

        foreach ( $idsUsuarios as $id ) {

            $this->_cursosPorInscricoesCF->remove(
                $id,
                array( $cursoUUID->bytes )
            );

        }
    }

    /**
     * @param array $idsGerenciadores
     * @param WeLearn_Cursos_Curso $doCurso
     */
    public function removerTodosGerenciadores(array $idsGerenciadores, WeLearn_Cursos_Curso $doCurso)
    {
        $cursoUUID = UUID::import( $doCurso->getId() );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $idsGerenciadores )
        );

        foreach ($idsGerenciadores as $id) {

            $this->_cursosPorGerenciadoresCF->remove(
                $id,
                array( $cursoUUID->bytes )
            );

        }
    }

    /**
     * @param array $idsUsuarios
     * @param WeLearn_Cursos_Curso $curso
     */
    public function removerTodosConvitesGerenciador(array $idsUsuarios, WeLearn_Cursos_Curso $curso)
    {
        $cursoUUID = UUID::import( $curso );

        $this->_usuariosPorCursoCF->remove(
            $cursoUUID->bytes,
            array( $idsUsuarios )
        );

        foreach ($idsUsuarios as $id) {

            $this->_cursosPorConviteGerenciadorCF->remove(
                $id,
                array( $cursoUUID->bytes )
            );

        }
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

        $this->_cursosPorNomeCF->insert(
            $primeiraLetra,
            array( $nomeSimplificado => $UUID->bytes )
        );

        $this->_cursosPorAreaCF->insert(
            '__todos__',
            array( $UUID->bytes => '' )
        );

        $this->_cursosPorAreaCF->insert(
            $dto->getSegmento()->getArea()->getId(),
            array( $UUID->bytes => '' )
        );

        $this->_cursosPorSegmentoCF->insert(
            $dto->getSegmento()->getId(),
            array( $UUID->bytes => '' )
        );

        $this->_cursosPorCriadorCF->insert(
            $dto->getCriador()->getId(),
            array( $UUID->bytes => '' )
        );

        $this->_usuariosPorCursoCF->insert(
            $UUID->bytes,
            array( $dto->getCriador()->getId() => $dto->getCriador()->getNivelAcesso() )
        );

        get_instance()->db->insert( $this->_mysql_tbl_name, $dto->toMySQL() );

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

        if ( $segmentoAtual['segmento'] != $dto->getSegmento()->getId() ) {

            $segmentoAtual = $this->_segmentoDAO->recuperar($segmentoAtual['segmento']);

            $this->_cursosPorAreaCF->remove(
                $segmentoAtual->getArea()->getId(),
                array( $UUID->bytes )
            );

            $this->_cursosPorSegmentoCF->remove(
                $segmentoAtual->getId(),
                array( $UUID->bytes )
            );

            $this->_cursosPorAreaCF->insert(
                $dto->getSegmento()->getArea()->getId(),
                array( $UUID->bytes => '' )
            );

            $this->_cursosPorSegmentoCF->insert(
                $dto->getSegmento()->getId(),
                array( $UUID->bytes => '' )
            );

        }

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        if ( ! is_null( $dto->getImagem() ) ) {
            $this->_imagemDAO->salvar($dto->getImagem());
        }

        if ( ! is_null( $dto->getConfiguracao() ) ) {
            $this->_configuracaoDAO->salvar($dto->getConfiguracao());
        }

        get_instance()->db->where( 'id', $dto->getId() )
                      ->update( $this->_mysql_tbl_name, $dto->toMySQL() );
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return void
     */
    public function descontinuar(WeLearn_Cursos_Curso $curso)
    {
         $this->remover( $curso->getId() );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarConfiguracao(array $dados = null)
    {
        return $this->_configuracaoDAO->criarNovo($dados);
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarImagem(array $dados = null)
    {
        return $this->_imagemDAO->criarNovo($dados);
    }

    /**
     * @param array $column
     * @param null|WeLearn_Cursos_Segmento $segmentoPadrao
     * @param null|WeLearn_Usuarios_GerenciadorPrincipal $criadorPadrao
     * @return WeLearn_Cursos_Curso
     */
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

    /**
     * @param array $columns
     * @param null|WeLearn_Cursos_Segmento $segmentoPadrao
     * @param null|WeLearn_Usuarios_GerenciadorPrincipal $criadorPadrao
     * @return array
     */
    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_Segmento $segmentoPadrao = null,
                                               WeLearn_Usuarios_GerenciadorPrincipal $criadorPadrao = null)
    {
        $listaCursosObjs = array();

        foreach ($columns as $column) {
            $listaCursosObjs[] = $this->_criarFromCassandra(
                $column,
                $segmentoPadrao,
                $criadorPadrao
            );
        }

        return $listaCursosObjs;
    }
}
