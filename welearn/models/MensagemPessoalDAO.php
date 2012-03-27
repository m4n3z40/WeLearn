<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 03/11/11
 * Time: 20:57
 * To change this template use File | Settings | File Templates.
 */


// como o allan explicou, existirá uma lista com o nome de todos os amigos que já trocaram mensagem com o usuario
// ao clicar em uma amigo será exibido a lista de conversas do usuario com este amigo, baseado na chave usuario1::usuario2
//usuarios_mensagem irá guardar o id da mensagem e a mensagem em si
//usuarios_mensagem_por_amigos irá guardar (key: usuario1::usuario2){array(id_mensagem:"")}
//usuario_mensagem_amigos ira guardar (key: destinatario)array(



class MensagemPessoalDAO extends WeLearn_DAO_AbstractDAO {

    protected $_nomeCF = 'usuarios_mensagem_pessoal';

    //indexes
    private $_nomeMPPorAmigosCF = 'usuarios_mensagem_por_amigos';//mantem os ids da mensagens trocadas entre dois usuarios agrupadas
    private $_nomeMPAmigosCF = 'usuarios_mensagem_amigos';//mantem a lista de amigos que já trocaram mensagens com o usuario pelo menos uma vez(id da column  vai ser o id do amigo)

    private $_MPPorAmigosCF;
    private $_MPAmigosCF;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    function __construct()
    {

        $phpCassa = WL_Phpcassa::getInstance();

        $this->_MPPorAmigosCF = $phpCassa->getColumnFamily($this->_nomeMPPorAmigosCF);
        $this->_MPAmigosCF = $phpCassa->getColumnFamily($this->_nomeMPAmigosCF);
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

        $remetente=$dto->getRemetente();
        $destinatario=$dto->getDestinatario();
        $arraySort = array($remetente->getId(), $destinatario->getId());
        sort($arraySort);
        $chave= implode('::', $arraySort);

        $this->_MPPorAmigosCF->insert($chave, array($UUID->bytes => ''));
        $this->_MPAmigosCF->insert($destinatario->getId(), array($remetente->getId() => ''));
        $this->_MPAmigosCF->insert($remetente->getId(), array($destinatario->getId() => ''));
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
        return array();
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
