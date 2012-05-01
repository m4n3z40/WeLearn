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
    protected $_nomeConvitePorUsuario= 'convites_convite_por_usuario';

    /**
     * @var ConviteDAO
     */
    protected $_conviteDao;
    protected $_convitePorUsuarioCF;


    public function __construct()
    {
        $this->_conviteDao= WeLearn_DAO_DAOFactory::create('ConviteDAO');
        $phpCassa = WL_Phpcassa::getInstance();
        $this->_convitePorUsuarioCF = $phpCassa->getColumnFamily($this->_nomeConvitePorUsuario);
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
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implementar este metodo
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
        // TODO: Implementar este metodo
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
        $chave=$this->gerarChave($dto);
        $dto->setId($chave);
        $this->_cf->insert($dto->getDestinatario()->getId(),array($dto->getId() => ''));
        $this->_conviteDao->salvar($dto);
        $this->_convitePorUsuarioCF->insert($chave,array(CassandraUtil::import($dto->getId())->bytes=>''));

    }

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

}
