<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 11:02
 * To change this template use File | Settings | File Templates.
 */
 
class ComentarioDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_comentario';

    private $_nomeComentariosPorPaginaCF = 'cursos_comentario_por_pagina';

    /**
     * @var ColumnFamily|null
     */
    private $_comentarioPorPaginaCF;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    /**
     * @var PaginaDAO
     */
    private $_paginaDao;

    function __construct()
    {
        $this->_comentarioPorPaginaCF = WL_Phpcassa::getInstance()->getColumnFamily(
            $this->_nomeComentariosPorPaginaCF
        );

        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_paginaDao = WeLearn_DAO_DAOFactory::create('PaginaDAO');
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $UUID = CassandraUtil::import( $id );

        $column = $this->_cf->get($UUID->bytes);

        return $this->_criarFromCassandra( $column );
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
            $count = 20;
        }

        if ( isset($filtros['pagina']) && $filtros['pagina'] instanceof WeLearn_Cursos_Conteudo_Pagina ) {
            return $this->recuperarTodosPorPagina($filtros['pagina'], $de, $ate, $count);
        }

        return array();
    }

    public function recuperarTodosPorPagina(WeLearn_Cursos_Conteudo_Pagina $pagina,
                                            $de = '',
                                            $ate = '',
                                            $count = 20)
    {
        if ($de != '') {
            $de = CassandraUtil::import( $de )->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import( $ate )->bytes;
        }

        $paginaUUID = CassandraUtil::import( $pagina->getId() );

        $ids = array_keys(
            $this->_comentarioPorPaginaCF->get(
                $paginaUUID->bytes,
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget($ids);

        return $this->_criarVariosFromCassandra($columns, $pagina);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Conteudo_Pagina) {
            return $this->recuperarQtdTotalPorPagina( $de );
        }

        return 0;
    }

    public function recuperarQtdTotalPorPagina(WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $paginaUUID = CassandraUtil::import( $pagina->getId() );

        return $this->_comentarioPorPaginaCF->get_count( $paginaUUID->bytes );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $comentarioRemovido = $this->recuperar( $id );

        $UUID = CassandraUtil::import( $id );
        $paginaUUID = CassandraUtil::import( $comentarioRemovido->getPagina()->getId() );

        $this->_cf->remove($UUID->bytes);

        $this->_comentarioPorPaginaCF->remove($paginaUUID->bytes, array($UUID->bytes));

        $comentarioRemovido->setPersistido(false);

        return $comentarioRemovido;
    }

    public function removerTodosPorPagina(WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $paginaUUID = CassandraUtil::import( $pagina->getId() );

        $idsRemover = array_keys(
            $this->_comentarioPorPaginaCF->get(
                $paginaUUID->bytes,
                null,
                '',
                '',
                false,
                1000000
            )
        );

        $this->_comentarioPorPaginaCF->remove( $paginaUUID->bytes );

        foreach ($idsRemover as $id) {
            $this->_cf->remove( $id );
        }
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_Comentario( $dados );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import( $dto->getId() );

        $dto->setDataAlteracao( time() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $paginaUUID = CassandraUtil::import( $dto->getPagina()->getId() );

        $dto->setId( $UUID->string );
        $dto->setDataEnvio( time() );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        $this->_comentarioPorPaginaCF->insert( $paginaUUID->bytes, array($UUID->bytes => '') );

        $dto->setPersistido(true);
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_Conteudo_Pagina $paginaPadrao = null,
                                         WeLearn_Usuarios_Usuario $usuarioPadrao = null)
    {
        $column['pagina'] = ($paginaPadrao instanceof WeLearn_Cursos_Conteudo_Pagina)
                            ? $paginaPadrao
                            : $this->_paginaDao->recuperar( $column['pagina'] );

        $column['criador'] = ($usuarioPadrao instanceof WeLearn_Usuarios_Usuario)
                            ? $usuarioPadrao
                            : $this->_usuarioDao->recuperar( $column['criador'] );

        $comentario = $this->criarNovo();
        $comentario->fromCassandra( $column );
        return $comentario;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Cursos_Conteudo_Pagina $paginaPadrao = null,
                                               WeLearn_Usuarios_Usuario $usuarioPadrao = null)
    {
        $listaComentarios = array();

        foreach ($columns as $column) {
            $listaComentarios[] = $this->_criarFromCassandra(
                $column,
                $paginaPadrao,
                $usuarioPadrao
            );
        }

        return $listaComentarios;
    }
}
