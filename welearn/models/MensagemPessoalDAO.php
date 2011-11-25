<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 03/11/11
 * Time: 20:57
 * To change this template use File | Settings | File Templates.
 */
 
class MensagemPessoalDAO extends WeLearn_DAO_AbstractDAO {

    protected $_nomeCF = 'usuarios_mensagem_pessoal';

    //indexes
    private $_nomeMPPorRemetenteCF = 'usuarios_mensagem_pessoal_por_remetente';
    private $_nomeMPPorDestinatarioCF = 'usuarios_mensagem_pessoal_por_destinatario';

    private $_MPPorRemetenteCF;
    private $_MPPorDestinatarioCF;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    function __construct()
    {
        parent::__construct();

        $phpCassa = WL_Phpcassa::getInstance();

        $this->_MPPorRemetenteCF = $phpCassa->getColumnFamily($this->_nomeMPPorRemetenteCF);
        $this->_MPPorDestinatarioCF = $phpCassa->getColumnFamily($this->_nomeMPPorDestinatarioCF);

        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();

        $dto->setId($UUID->string);
        $dto->setDataEnvio(time());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());

        $this->_MPPorRemetenteCF->insert($dto->getRemetente()->getId(), array($UUID->bytes => ''));
        $this->_MPPorDestinatarioCF->insert($dto->getDestinatario()->getId(), array($UUID->bytes => ''));
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $UUID = CassandraUtil::import($dto->getId());

        $this->_cf->insert($UUID->bytes, $dto->toCassandra());
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

        if (isset($filtros['remetente']) && $filtros['remetente'] instanceof WeLearn_Usuarios_Usuario) {
            return $this->recuperarTodosPorRemetente($filtros['remerente'], $de, $ate, $count);
        }

        if (isset($filtros['destinatario']) && $filtros['destinatario'] instanceof WeLearn_Usuarios_Usuario) {
            return $this->recuperarTodosPorDestinatario($filtros['destinatario'], $de, $ate, $count);
        }

        return array();
    }

    public function recuperarTodosPorRemetente(WeLearn_Usuarios_Usuario $remetente, $de = '', $ate = '', $count = 10)
    {
        if ( $de != '' ) {
            $de = CassandraUtil::import($de)->bytes;
        }
        if ( $ate != '' ) {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsMensagens = $this->_MPPorRemetenteCF->get($remetente->getId(), null, $de, $ate, true, $count);

        $columns = $this->_cf->multiget($idsMensagens);

        return $this->_criarVariosFromCassandra($columns, $remetente);
    }

    public function recuperarTodosPorDestinatario(WeLearn_Usuarios_Usuario $destinatario, $de = '', $ate = '', $count = 10)
    {
        if ( $de != '' ) {
            $de = CassandraUtil::import($de)->bytes;
        }
        if ( $ate != '' ) {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $idsMensagens = $this->_MPPorDestinatarioCF->get($destinatario->getId(), null, $de, $ate, true, $count);

        $columns = $this->_cf->multiget($idsMensagens);

        return $this->_criarVariosFromCassandra($columns, null, $destinatario);
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
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        return 0;
    }

    public function recuperarQtdPorRemetente(WeLearn_Usuarios_Usuario $remetente)
    {
        return $this->_MPPorRemetenteCF->get_count($remetente->getId());
    }

    public function recuperarQtdPorDestinatario(WeLearn_Usuarios_Usuario $destinatario)
    {
        return $this->_MPPorDestinatarioCF->get_count($destinatario->getId());
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        if ( ! ($id instanceof UUID) ) {
            $id = CassandraUtil::import($id);
        }

        $mensagem = $this->recuperar($id);

        $this->_cf->remove($id->bytes);

        $this->_MPPorRemetenteCF->remove($mensagem->getRemetente()->getId(), array($id->bytes));
        $this->_MPPorDestinatarioCF->remove($mensagem->getDestinatario()->getId(), array($id->bytes));

        $mensagem->setPersistido(false);

        return $mensagem;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Usuarios_MensagemPessoal($dados);
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Usuarios_Usuario $remetentePadrao = null,
                                         WeLearn_Usuarios_Usuario $destinatarioPadrao = null)
    {
        if ($remetentePadrao instanceof WeLearn_Usuarios_Usuario) {
            $column['remetente'] = $remetentePadrao;
        } else {
            $column['remetente'] = $this->_usuarioDao->recuperar($column['remetente']);
        }

        if ($destinatarioPadrao instanceof WeLearn_Usuarios_Usuario) {
            $column['destinatario'] = $destinatarioPadrao;
        } else {
            $column['destinatario'] = $this->_usuarioDao->recuperar($column['destinatario']);
        }

        $mensagem = $this->criarNovo();
        $mensagem->fromCassandra($column);

        return $mensagem;
    }

    private function _criarVariosFromCassandra(array $columns,
                                               WeLearn_Usuarios_Usuario $remetentePadrao = null,
                                               WeLearn_Usuarios_Usuario $destinatarioPadrao = null)
    {
        $arrayMensagens = array();

        foreach ( $columns as $column ) {
            $arrayMensagens[] = $this->_criarFromCassandra($column, $remetentePadrao, $destinatarioPadrao);
        }

        return $arrayMensagens;
    }
}
