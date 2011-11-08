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
    private $_nomeMPPorStatusCF = 'usuarios_mensagem_pessoal_por_status'; // Super

    private $_MPPorRemetenteCF;
    private $_MPPorDestinatarioCF;
    private $_MPPorStatusCF;

    function __construct()
    {
        parent::__construct();

        $phpCassa = WL_Phpcassa::getInstance();

        $this->_MPPorRemetenteCF = $phpCassa->getColumnFamily($this->_nomeMPPorRemetenteCF);
        $this->_MPPorDestinatarioCF = $phpCassa->getColumnFamily($this->_nomeMPPorDestinatarioCF);
        $this->_MPPorStatusCF = $phpCassa->getColumnFamily($this->_nomeMPPorStatusCF);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        // TODO: Implement _adicionar() method.
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implement _atualizar() method.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implement recuperarTodos() method.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implement recuperar() method.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implement recuperarQtdTotal() method.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        // TODO: Implement remover() method.
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        // TODO: Implement criarNovo() method.
    }
}
