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
    function __construct()
    {
        // TODO: Implement __construct() method.
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
        return new WeLearn_Notificacoes_Notificacao( $dados );
    }
}
