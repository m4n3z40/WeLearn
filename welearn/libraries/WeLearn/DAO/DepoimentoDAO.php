<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 11/08/11
 * Time: 19:54
 * To change this template use File | Settings | File Templates.
 */
 
class DepoimentoDAO extends WeLearn_DAO_AbstractDAO
{
     /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        // TODO: Implementar este metodo.
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
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
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
     * @return bool
     */
    public function salvar(WeLearn_DTO_IDTO &$dto)
    {
        return parent::salvar($dto);
    }

    /**
     * @param \ColumnFamily $cf
     */
    public function setCf($cf)
    {
        parent::setCf($cf);
    }

     /**
     * @return \ColumnFamily
     */
    public function getCf()
    {
        return parent::getCf();
    }

     /**
     * @return array
     */
    public function getInfoColunas()
    {
        return parent::getInfoColunas();
    }

     /**
     * @return string
     */
    public function getNomeCF()
    {
        return parent::getNomeCF();
    }

    /**
     * @param inteiro $maxPag
     * @param inteiro $inicioPag
     * @param array $filtros
     * @return void
     */
    public function recuperarTodosAceitos(inteiro $maxPag, inteiro $inicioPag, array $filtros)
    {
      // TODO: Implementar este metodo.
    }

    /**
     * @param inteiro $maxPag
     * @param inteiro $inicioPag
     * @param array $filtros
     * @return void
     */
    public function recuperarTodosEmEspera(inteiro $maxPag, inteiro $inicioPag, array $filtros)
    {
      // TODO: Implementar este metodo.
    }

    /**
     * @param WeLearn_Cursos_Curso $curso
     * @return void
     */
    public function recuperarUltimos(WeLearn_Cursos_Curso $curso)
    {
      // TODO: Implementar este metodo.
    }

    

}
