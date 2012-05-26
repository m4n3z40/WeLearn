<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 09:31
 * To change this template use File | Settings | File Templates.
 */
 
class AnotacaoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_anotacao';

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    public function __construct()
    {
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        if ( is_array($id) && isset($id['usuario']) && isset($id['pagina']) ) {
            return $this->recuperarPorUsuario( $id['pagina'], $id['usuario'] );
        }

        return null;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return WeLearn_DTO_IDTO
     */
    public function recuperarPorUsuario(WeLearn_Cursos_Conteudo_Pagina $pagina,
                                        WeLearn_Usuarios_Usuario $usuario)
    {
        $paginaUUID = UUID::import( $pagina->getId() );

        $column = $this->_cf->get( $paginaUUID->bytes, array( $usuario->getId() ) );

        return $this->_criarFromCassandra($column, $pagina, $usuario);
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        if ( isset( $filtros['count'] ) ) {
            $count = $filtros['count'];
        } else {
            $count = 20;
        }

        if ( isset( $filtros['pagina'] ) ) {
            return $this->recuperarTodosPorPagina( $filtros['pagina'], $de, $ate, $count );
        }
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorPagina(WeLearn_Cursos_Conteudo_Pagina $pagina,
                                            $de = '',
                                            $ate = '',
                                            $count = 20)
    {
        $paginaUUID = UUID::import( $pagina->getId() );

        $columns = $this->_cf->get( $paginaUUID->bytes, null, $de, $ate, false, $count );

        return $this->_criarVariosFromCassandra( $columns, $pagina );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Cursos_Conteudo_Pagina) {
            return $this->recuperarQtdTotalPorPagina($de);
        }

        return 0;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @return int
     */
    public function recuperarQtdTotalPorPagina(WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $paginaUUID = UUID::import( $pagina->getId() );

        return $this->_cf->get_count( $paginaUUID->bytes );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        if ( is_array($id) && isset($id['usuario']) && isset($id['pagina']) ) {
            return $this->removerPorUsuario( $id['pagina'], $id['usuario'] );
        }

        return null;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return WeLearn_DTO_IDTO
     */
    public function removerPorUsuario(WeLearn_Cursos_Conteudo_Pagina $pagina,
                                      WeLearn_Usuarios_Usuario $usuario)
    {
        $anotacaoRemovida = $this->recuperarPorUsuario($pagina, $usuario);

        $paginaUUID = UUID::import( $pagina->getId() );

        $this->_cf->remove( $paginaUUID->bytes, array( $usuario->getId() ) );

        return $anotacaoRemovida;
    }

    /**
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     */
    public function removerTodosPorPagina(WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $paginaUUID = UUID::import( $pagina->getId() );

        $this->_cf->remove( $paginaUUID->bytes );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Cursos_Conteudo_Anotacao( $dados );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $paginaUUID = UUID::import( $dto->getPagina()->getId() );

        $this->_cf->insert( $paginaUUID->bytes, $dto->toCassandra() );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $paginaUUID = UUID::import( $dto->getPagina()->getId() );

        $this->_cf->insert( $paginaUUID->bytes, $dto->toCassandra() );

        $dto->setPersistido(true);
    }

    /**
     * @param array $column
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param null|WeLearn_Usuarios_Usuario $usuario
     * @return WeLearn_DTO_IDTO
     */
    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_Conteudo_Pagina $pagina,
                                         WeLearn_Usuarios_Usuario $usuario = null)
    {
        $usuario = ( $usuario instanceof WeLearn_Usuarios_Usuario )
                   ? $usuario : $this->_usuarioDao->recuperar( key( $column ) );

        $realColumn = array(
            'conteudo' => $column[ $usuario->getId() ],
            'usuario' => $usuario,
            'pagina' => $pagina,
        );

        $anotacao = $this->criarNovo();
        $anotacao->fromCassandra( $realColumn );

        return $anotacao;
    }

    /**
     * @param array $columns
     * @param WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param null|WeLearn_Usuarios_Usuario $usuario
     * @return array
     */
    private function _criarVariosFromCassandra(array $columns,
                                             WeLearn_Cursos_Conteudo_Pagina $pagina,
                                             WeLearn_Usuarios_Usuario $usuario = null)
    {
        $anotacoes = array();

        foreach ($columns as $column) {
            $anotacoes[] = $this->_criarFromCassandra($column, $pagina, $usuario);
        }

        return $anotacoes;
    }
}
