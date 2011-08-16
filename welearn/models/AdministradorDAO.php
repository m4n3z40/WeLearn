<?php
/**
 * Created by Thiago Monteiro
 * Date: 09/08/11
 * Time: 08:55
 *
 * Description:
 *
 */

class WeLearn_DAO_AdminstradorDAO extends WeLearn_DAO_AbstractDAO
{


    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
         /*implementar tudo */
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
         /*implementar tudo */
    }  

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        /*implementar tudo */
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function salvar(WeLearn_DTO_IDTO &$dto)
    {
        /*implementar tudo */
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        /*implementar tudo */
    }

     /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        /*implementar tudo */
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        /*implementar tudo */
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        /*implementar tudo*/
    }
}
?>
