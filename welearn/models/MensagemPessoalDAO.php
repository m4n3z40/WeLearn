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
    private $_nomeMPPorAmigosCF = 'usuarios_mensagem_pessoal_por_amigos';
    private $_nomeMPListaAmigosCF = 'usuarios_mensagem_pessoal_lista_amigos';


    private $_MPPorAmigosCF;
    private $_MPListaAmigosCF;

    /**
     * @var UsuarioDAO
     */
    private $_usuarioDao;

    function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_MPPorAmigosCF = $phpCassa->getColumnFamily($this->_nomeMPPorAmigosCF);
        $this->_MPListaAmigosCF = $phpCassa->getColumnFamily($this->_nomeMPListaAmigosCF);
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
        $array_sort= array($remetente->getId(),$destinatario->getId());
        sort($array_sort);
        $chave_amizade=implode('::',$array_sort);
        $this->_MPPorAmigosCF->insert($chave_amizade,array($UUID->bytes => ''));
        $this->_MPListaAmigosCF->insert($dto->getRemetente()->getId(), array($dto->getDestinatario()->getId() => $dto->getDestinatario()->getId()));
        $this->_MPListaAmigosCF->insert($dto->getDestinatario()->getId(), array($dto->getRemetente()->getId() => $dto->getRemetente()->getId()));
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

    }


    public function recuperarTodosPorUsuario($remetente, $destinatario, $de = '',$ate = '',$count = 10)
    {
        $chave = $this->gerarChave($remetente->getId(), $destinatario->getId());

        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }

        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        $listaMensagens=array_keys($this->_MPPorAmigosCF->get($chave,null,$de,$ate,true,$count));
        $resultado=$this->_cf->multiget($listaMensagens);
        return $this->_criarVariosFromCassandra($resultado);

    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */

    public function recuperarListaAmigosMensagens(WeLearn_DTO_IDTO &$dto)
    {
        return $this->_MPListaAmigosCF->get($dto->getID());
    }


    public function gerarChave($idAmigo,$idUsuario)
    {
        $Array= array($idAmigo,$idUsuario);
        sort($Array);
        $chave=implode('::',$Array);
        return $chave;
    }

    public function recuperar($chave)
    {
        $listaMensagens=$this->_MPPorAmigosCF->get($chave);
        $aux=array();
        foreach($listaMensagens as $key=>$value)
        {
            $aux[]=$value;
        }
        $resultado=$this->_cf->multiget($aux);
        $cassandra=$this->_criarVariosFromCassandra($resultado);
        return $cassandra;
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
        $UUID = CassandraUtil::import($id)->bytes;
        $mensagem=$this->_cf->get($UUID);
        $validador=$this->verificarSeHaMensagens($mensagem['remetente'],$mensagem['destinatario']);
        if($validador==1)
        {
         $this->_MPListaAmigosCF->remove($mensagem['remetente'],array($mensagem['destinatario']));
         $this->_MPListaAmigosCF->remove($mensagem['destinatario'],array($mensagem['remetente']));
        }
        $array_sort= array($mensagem['remetente'],$mensagem['destinatario']);
        sort($array_sort);
        $chave_amizade=implode('::',$array_sort);
        $this->_cf->remove($UUID);
        $this->_MPPorAmigosCF->remove($chave_amizade,array($UUID));
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Usuarios_MensagemPessoal($dados);
    }

    private function _criarFromCassandra(array $column)
    {
        $column['remetente'] = $this->_usuarioDao->recuperar($column['remetente']);
        $column['destinatario'] = $this->_usuarioDao->recuperar($column['destinatario']);
        $mensagem = $this->criarNovo();
        $mensagem->fromCassandra($column);

        return $mensagem;
    }

    private function _criarVariosFromCassandra(array $columns)
    {
        $arrayMensagens = array();

        foreach ( $columns as $column ) {
            $arrayMensagens[] = $this->_criarFromCassandra($column);
        }

        return $arrayMensagens;
    }


    private function verificarSeHaMensagens($amigo,$usuario)
    {
        $array_sort= array($amigo,$usuario);
        sort($array_sort);
        $chave_amizade=implode('::',$array_sort);
        $qtd=$this->_MPPorAmigosCF->get_count($chave_amizade);
        return $qtd;
    }
}