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




    protected $_convitePorRemetente;
    protected $_convitePorDestinatario;


    public function __construct()
    {
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_convitePorRemetente = $phpCassa->getColumnFamily($this->_nomeConvitePorRemetente);
        $this->_convitePorDestinatario=$phpCassa->getColumnFamily($this->_nomeConvitePorDestinatario);
    }


     /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implementar este metodo
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = '', $ate = '', array $filtros = null)
    {
        $chavesConvite=array_keys($this->_cf->get($filtros['idUsuario'], null, $de, $ate, true, $filtros['count']));// recupera as chaves dos convites recebidos pelo usuario
        $idConvites=$this->_convitePorUsuarioCF->multiget($chavesConvite);
        $aux=array();
        foreach($idConvites as $key => $value)
        {
            foreach ($value as $chave => $valor)
            {
                $aux[]=$chave;
            }
        }
        $filtros['convites']=$aux;
        $convites=$this->_conviteDao->recuperarTodos($de='',$ate='',$filtros);
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
    public function remover($id)
    {
        //$this->_cf->remove($id);
        //$this->_convitePorUsuarioCF->remove($destinatario,);
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
        $this->_convitePorRemetente->insert($dto->getRemetente()->getId(),array($dto->getDestinatario()->getId() => $UUID->bytes));
    }


/*
    public function recuperar_por_chave($dto)
    {
        $chave=$this->gerarChave($dto);
        $resultado=$this->_convitePorUsuarioCF->get($chave);
        return $resultado;
    }

    private function gerarChave($dto)
    {
        $Array= array($dto->getDestinatario()->getId(),$dto->getRemetente()->getId());
        sort($Array);
        $chave=implode('::',$Array);
        return $chave;
    }
*/
}
