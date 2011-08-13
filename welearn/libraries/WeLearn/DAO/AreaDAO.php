<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 09:40
 * To change this template use File | Settings | File Templates.
 */
 
class AreaDAO extends WeLearn_DAO_AbstractDAO {
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
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implementar metodo
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implementar metodo
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        // TODO: Implementar metodo
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        // TODO: Implementar metodo
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implementar metodo
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        // TODO: Implementar metodo
    }

    public function salvar(WeLearn_DTO_IDTO &$dto)
    {
        return parent::salvar($dto);
    }

    public function getNomeCF()
    {
        return parent::getNomeCF();
    }

    public function getInfoColunas()
    {
        return parent::getInfoColunas();
    }

    public function getCf()
    {
        return parent::getCf();
    }

    public function setCf($cf)
    {
        parent::setCf($cf);
    }


}
