<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Suporte Técnico
 * Date: 11/08/11
 * Time: 08:36
 * To change this template use File | Settings | File Templates.
 */
 
class AlunoDAO extends WeLearn_DAO_AbstractDAO{


    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: implementar este metodo.
    }


    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Curso $Curso
     * @return int
     */
    public function recuperarQtdTotalFormados(WeLearn_Cursos_Curso $Curso)
    {
        // TODO: Implementar este metodo.
    }


    /**
     * @param WeLearn_Cursos_Curso $Curso
     * @return double
     */
    public function recuperarMediaCRFinal(WeLearn_Cursos_Curso $Curso)
    {
        //TODO: Implementar este metodo.
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        // TODO: Implementar este metodo.
    }
}
