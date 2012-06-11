<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/5/12
 * Time: 1:01 AM
 * To change this template use File | Settings | File Templates.
 */
class NotificacaoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'notificacoes_notificacao';
    
    private $_nomeNotificacoesPorUsuarioCF = 'notificacoes_notificacao_por_usuario';
    private $_nomeNotificacoesLidasPorUsuario = 'notificacoes_notificacao_lida_por_usuario';
    private $_nomeNotificacoesNovasPorUsuario = 'notificacoes_notificacao_nova_por_usuario';

    /**
     * @var ColumnFamily|null
     */
    private $_notificacoesPorUsuarioCF;

    /**
     * @var ColumnFamily|null
     */
    private $_notificacoesLidasPorUsuarioCF;

    /**
     * @var ColumnFamily|null
     */
    private $_notificacoesNovasPorUsuarioCF;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;
    
    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();
        
        $this->_notificacoesPorUsuarioCF = $phpCassa->getColumnFamily(
            $this->_nomeNotificacoesPorUsuarioCF
        );
        
        $this->_notificacoesLidasPorUsuarioCF = $phpCassa->getColumnFamily(
            $this->_nomeNotificacoesLidasPorUsuario
        );
        
        $this->_notificacoesNovasPorUsuarioCF = $phpCassa->getColumnFamily(
            $this->_nomeNotificacoesNovasPorUsuario
        );

        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param SplObjectStorage $listaNotificacoes
     * @return void
     */
    public function adicionarVarios(SplObjectStorage &$listaNotificacoes)
    {
        $batchNotificacoes = array();
        $batchNotificacoesPorUsuario = array();

        foreach ($listaNotificacoes as $notificacao) {

            $UUID = UUID::mint();
            $notificacao->setId( $UUID->string );
            $usuarioId = $notificacao->getDestinatario()->getId();

            $batchNotificacoes[ $UUID->bytes ] = $notificacao->toCassandra();
            $batchNotificacoesPorUsuario[ $usuarioId ] = array( $UUID->bytes => '' );

        }

        $this->_cf->batch_insert( $batchNotificacoes );
        $this->_notificacoesPorUsuarioCF->batch_insert( $batchNotificacoesPorUsuario );
        $this->_notificacoesNovasPorUsuarioCF->batch_insert( $batchNotificacoesPorUsuario );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        
        $dto->setId( $UUID->string );

        $this->_cf->insert( $UUID->bytes, $dto->toCassandra() );

        $usuarioId = $dto->getDestinatario()->getId();

        $this->_notificacoesPorUsuarioCF->insert(
            $usuarioId,
            array( $UUID->bytes => '' )
        );
        $this->_notificacoesNovasPorUsuarioCF->insert(
            $usuarioId,
            array( $UUID->bytes => '' )
        );

        $dto->setPersistido( true );
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = UUID::import( $dto->getId() )->bytes;

        $this->_cf->insert( $UUID, $dto->toCassandra() );

        $usuarioId = $dto->getDestinatario()->getId();

        $this->_notificacoesLidasPorUsuarioCF->remove(
            $usuarioId,
            array( $UUID )
        );
        $this->_notificacoesNovasPorUsuarioCF->remove(
            $usuarioId,
            array( $UUID )
        );

        if ( $dto->getStatus() === WeLearn_Notificacoes_StatusNotificacao::LIDO ) {

            $this->_notificacoesLidasPorUsuarioCF->insert(
                $usuarioId,
                array( $UUID => '' )
            );

        } else {

            $this->_notificacoesNovasPorUsuarioCF->insert(
                $usuarioId,
                array( $UUID => '' )
            );

        }
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if ( isset( $filtros['count'] ) ) {
            $count = $filtros['count'];
        } else {
            $count = 20;
        }

        if ( isset( $filtros['usuario'] ) ) {

            if ( isset( $filtros['status'] ) ) {

                switch( $filtros['status'] ) {
                    case WeLearn_Notificacoes_StatusNotificacao::NOVO:
                        return $this->recuperarTodosNovasPorUsuario(
                            $filtros['usuario'],
                            $de,
                            $ate,
                            $count
                        );
                    case WeLearn_Notificacoes_StatusNotificacao::LIDO:
                        return $this->recuperarTodosLidasPorUsuario(
                            $filtros['usuario'],
                            $de,
                            $ate,
                            $count
                        );
                    default:
                }

            }

            return $this->recuperarTodosPorUsuario(
                $filtros['usuario'],
                $de,
                $ate,
                $count
            );
        }

        return array();
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosPorUsuario(WeLearn_Usuarios_Usuario $usuario, 
                                             $de = '', 
                                             $ate ='', 
                                             $count = 20)
    {
        if ($de != '') {
            $de = UUID::import( $de )->bytes;
        }
        
        if ($ate != '') {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_notificacoesPorUsuarioCF->get(
                $usuario->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns, $usuario );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosNovasPorUsuario(WeLearn_Usuarios_Usuario $usuario, 
                                                  $de = '', 
                                                  $ate ='', 
                                                  $count = 20)
    {
        if ($de != '') {
            $de = UUID::import( $de )->bytes;
        }

        if ($ate != '') {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_notificacoesNovasPorUsuarioCF->get(
                $usuario->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns, $usuario );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @param string $de
     * @param string $ate
     * @param int $count
     * @return array
     */
    public function recuperarTodosLidasPorUsuario(WeLearn_Usuarios_Usuario $usuario, 
                                                  $de = '', 
                                                  $ate ='', 
                                                  $count = 20)
    {
        if ($de != '') {
            $de = UUID::import( $de )->bytes;
        }

        if ($ate != '') {
            $ate = UUID::import( $ate )->bytes;
        }

        $ids = array_keys(
            $this->_notificacoesLidasPorUsuarioCF->get(
                $usuario->getId(),
                null,
                $de,
                $ate,
                true,
                $count
            )
        );

        $columns = $this->_cf->multiget( $ids );

        return $this->_criarVariosFromCassandra( $columns, $usuario );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $UUID = UUID::import( $id )->bytes;

        $column = $this->_cf->get( $UUID );

        return $this->_criarFromCassandra( $column );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        if ($de instanceof WeLearn_Usuarios_Usuario) {
            return $this->recuperarQtdTotalPorUsuario( $de );
        }

        return 0;
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return int
     */
    public function recuperarQtdTotalPorUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        return $this->_notificacoesPorUsuarioCF->get_count( $usuario->getId() );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return int
     */
    public function recuperarQtdTotalNovasPorUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        return $this->_notificacoesNovasPorUsuarioCF->get_count( $usuario->getId() );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     * @return int
     */
    public function recuperarQtdTotalLidasPorUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        return $this->_notificacoesLidasPorUsuarioCF->get_count( $usuario->getId() );
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $UUID = UUID::import( $id )->bytes;

        $notificacaoRemovida = $this->recuperar( $id );

        $this->_cf->remove( $UUID );

        $usuarioId = $notificacaoRemovida->getDestinatario()->getId();

        $this->_notificacoesPorUsuarioCF->remove( $usuarioId, array( $UUID ) );
        $this->_notificacoesNovasPorUsuarioCF->remove( $usuarioId, array( $UUID ) );
        $this->_notificacoesLidasPorUsuarioCF->remove( $usuarioId, array( $UUID ) );
    }

    /**
     * @param WeLearn_Usuarios_Usuario $usuario
     */
    public function removerTodosPorUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        $qtdTotal = $this->recuperarQtdTotalPorUsuario( $usuario );

        $ids = array_keys(
            $this->_notificacoesPorUsuarioCF->get(
                $usuario->getId(),
                null,
                '',
                '',
                false,
                $qtdTotal
            )
        );

        for ($i = 0; $i < $qtdTotal; $i++) {
            $this->_cf->remove( $ids[$i] );
        }

        $this->_notificacoesPorUsuarioCF->remove( $usuario->getId() );
        $this->_notificacoesNovasPorUsuarioCF->remove( $usuario->getId() );
        $this->_notificacoesLidasPorUsuarioCF->remove( $usuario->getId() );
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Notificacoes_Notificacao( $dados );
    }

    /**
     * @param array $column
     * @param null|WeLearn_Usuarios_Usuario $destinatario
     * @return WeLearn_DTO_IDTO
     */
    public function _criarFromCassandra(array $column, WeLearn_Usuarios_Usuario $destinatario = null)
    {
        $column['destinatario'] = ( $destinatario instanceof WeLearn_Usuarios_Usuario )
                                  ? $destinatario
                                  : $this->_usuarioDao->recuperar( $column['destinatario'] );

        $notificacao = $this->criarNovo();
        $notificacao->fromCassandra( $column );

        return $notificacao;
    }

    /**
     * @param array $columns
     * @param null|WeLearn_Usuarios_Usuario $destinatario
     * @return array
     */
    public function _criarVariosFromCassandra(array $columns, WeLearn_Usuarios_Usuario $destinatario = null)
    {
        $listaNotificacoes = array();

        foreach ($columns as $column) {
            $listaNotificacoes[] = $this->_criarFromCassandra( $column, $destinatario );
        }

        return $listaNotificacoes;
    }
}
