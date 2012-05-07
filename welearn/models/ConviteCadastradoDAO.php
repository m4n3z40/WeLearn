<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 11:07
 * To change this template use File | Settings | File Templates.
 */

class ConviteCadastradoDAO extends WeLearn_DAO_AbstractDAO
{

    protected $_nomeCF = 'convites_convite_cadastrado';
    protected $_nomeConvitePorRemetente= 'convites_convite_cadastrado_por_remetente';
    protected $_nomeConvitePorDestinatario= 'convites_convite_cadastrado_por_destinatario';
    protected $_nomeConvitePorDestinatario_por_data = 'convites_convite_cadastrado_por_destinatario_por_data';
    protected $_nomeConvitePorRemetente_por_data= 'convites_convite_cadastrado_por_remetente_por_data';




    protected $_convitePorRemetente;
    protected $_convitePorDestinatario;
    protected $_convitePorDestinatarioPorData;
    protected $_convitePorRemetentePorData;


    /**
     * @var UsuarioDAO
     */
    private $_cursoDao;
    private $_usuarioDao;

    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_convitePorRemetente = $phpCassa->getColumnFamily($this->_nomeConvitePorRemetente);
        $this->_convitePorDestinatario=$phpCassa->getColumnFamily($this->_nomeConvitePorDestinatario);
        $this->_convitePorDestinatarioPorData=$phpCassa->getColumnFamily($this->_nomeConvitePorDestinatario_por_data);
        $this->_convitePorRemetentePorData=$phpCassa->getColumnFamily($this->_nomeConvitePorRemetente_por_data);
        $this->_usuarioDao = WeLearn_DAO_DAOFactory::create('UsuarioDAO');
        $this->_cursoDao = WeLearn_DAO_DAOFactory::create('CursoDAO');
    }


    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($idConvite)
    {
        $idConvite= CassandraUtil::import($idConvite)->bytes;
        return $this->_criarFromCassandra($this->_cf->get($idConvite));
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        if ($de != '') {
            $de = CassandraUtil::import($de)->bytes;
        }
        if ($ate != '') {
            $ate = CassandraUtil::import($ate)->bytes;
        }

        if($filtros['tipoConvite'] == 'enviados')
        {
            $convitesEnviados=$this->_convitePorRemetentePorData->get($filtros['usuarioObj']->getId(),null,$de,$ate,true,$filtros['count']);
            $cassandra=$this->_cf->multiget($convitesEnviados);
            $convites=$this->_criarVariosFromCassandra($cassandra,$filtros['usuarioObj']);

        }

        if($filtros['tipoConvite'] == 'recebidos')
        {
            $convitesRecebidos=$this->_convitePorDestinatarioPorData->get($filtros['usuarioObj']->getId(),null,$de,$ate,true,$filtros['count']);
            $cassandra=$this->_cf->multiget($convitesRecebidos);
            $convites=$this->_criarVariosFromCassandra($cassandra,null,$filtros['usuarioObj']);
        }


        return $convites;

    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implementar este metodo
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($idConvite)
    {
        $convite=$this->recuperar($idConvite);
        $idConvite=CassandraUtil::import($idConvite)->bytes;
        $this->_cf->remove($idConvite);
        $this->_convitePorDestinatarioPorData->remove($convite->getDestinatario()->getId(),array($idConvite));
        $this->_convitePorRemetentePorData->remove($convite->getRemetente()->getId(),array($idConvite));
        $this->_convitePorRemetente->remove($convite->getRemetente()->getId(),array($convite->getDestinatario()->getId()));
        $this->_convitePorDestinatario->remove($convite->getDestinatario()->getId(),array($convite->getRemetente()->getId()));
        $convite->setPersistido(false);
        return $convite;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Convites_ConviteCadastrado($dados);
    }


    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function recuperarPendentes(WeLearn_Usuarios_Usuario $usuarioAutenticado,
                                       WeLearn_Usuarios_Usuario $usuarioPerfil)
    {
        try{
            $enviado = $this->_convitePorRemetente->get($usuarioAutenticado->getId(),array($usuarioPerfil->getId()));
            $convite = $this->_criarFromCassandra($this->_cf->get($enviado[$usuarioPerfil->getId()]),
                $usuarioAutenticado,$usuarioPerfil);
            return $convite;

        }catch(cassandra_NotFoundException $e)
        {
            $recebido = $this->_convitePorDestinatario->get($usuarioAutenticado->getId(),array($usuarioPerfil->getId()));
            $convite = $this->_criarFromCassandra($this->_cf->get($recebido[$usuarioPerfil->getId()]),
                $usuarioPerfil,$usuarioAutenticado);
            return $convite;
        }

        // funçao sempre retorna um convite
    }


    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implementar este metodo
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $UUID = UUID::mint();
        $dto->setId($UUID->string);
        $this->_cf->insert($UUID->bytes,$dto->toCassandra());
        $this->_convitePorDestinatario->insert($dto->getDestinatario()->getId(),array($dto->getRemetente()->getId() => $UUID->bytes));
        $this->_convitePorRemetente->insert($dto->getRemetente()->getId(),array($dto->getDestinatario()->getId()=> $UUID->bytes));
        $this->_convitePorDestinatarioPorData->insert($dto->getDestinatario()->getId(),array($UUID->bytes => $UUID->bytes ));
        $this->_convitePorRemetentePorData->insert($dto->getRemetente()->getId(),array($UUID->bytes => $UUID->bytes));
    }


    private function _criarFromCassandra(array $column, WeLearn_Usuarios_Usuario $remetentePadrao = null,
                                         WeLearn_Usuarios_Usuario $destinatarioPadrao = null)

    {
        if($column['remetente'])
            $column['remetente'] = ($remetentePadrao instanceof WeLearn_Usuarios_Usuario)
                ? $remetentePadrao
                : $this->_usuarioDao->recuperar($column['remetente']);

        if($column['destinatario'])
            $column['destinatario'] = ($destinatarioPadrao instanceof WeLearn_Usuarios_Usuario)
                ? $destinatarioPadrao
                : $this->_usuarioDao->recuperar($column['destinatario']);

        if($column['paraCurso']) {
            $column['paraCurso'] = $this->_cursoDao->recuperar($column['paraCurso']);
        } else {
            unset($column['paraCurso']);
        }

        $convite = $this->criarNovo();
        $convite->fromCassandra($column);

        return $convite;
    }

    private function _criarVariosFromCassandra(array $columns,WeLearn_Usuarios_Usuario $remetentePadrao = null,
                                               WeLearn_Usuarios_Usuario $destinatarioPadrao = null)
    {
        $arrayConvites = array();

        foreach ( $columns as $column ) {
            $arrayConvites[] = $this->_criarFromCassandra($column, $remetentePadrao, $destinatarioPadrao);
        }

        return $arrayConvites;
    }


}