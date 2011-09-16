<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 12/08/11
 * Time: 08:03
 * To change this template use File | Settings | File Templates.
 */
 
class ConfiguracaoUsuarioDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'usuarios_configuracao';

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $this->_cf->insert($dto->getUsuarioId(), $dto->toCassandra());
        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $this->_cf->insert($dto->getUsuarioId(), $dto->toCassandra());
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        //TODO: Implement recuperarTodos() method.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $dados_configuracao = $this->_cf->get($id);

        $configuracao = new WeLearn_Usuarios_ConfiguracaoUsuario();
        $configuracao->fromCassandra($dados_configuracao);
        $configuracao->setPersistido(true);
        
        return $configuracao;
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
        $configuracaoRemovida = $this->recuperar($id);

        $this->_cf->remove($id);

        return $configuracaoRemovida;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        return new WeLearn_Usuarios_ConfiguracaoUsuario();
    }
}