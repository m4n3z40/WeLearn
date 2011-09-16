<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 02:48
 * 
 * Description:
 *
 */

interface WeLearn_DAO_IDAO 
{
    /**
     * @abstract
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null);

    /**
     * @abstract
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id);

    /**
     * @abstract
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null);

    /**
     * @abstract
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    public function salvar(WeLearn_DTO_IDTO &$dto);

    /**
     * @abstract
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id);

    /**
     * @abstract
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null);
}
