<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago Monteiro
 * Date: 11/08/11
 * Time: 09:31
 * To change this template use File | Settings | File Templates.
 */
 
class AnotacaoDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_anotacao';

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
        // TODO: Implementar este metodo
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
        // TODO: Implementar este metodo
    }

    private function _criarFromCassandra(array $column,
                                         WeLearn_Cursos_Conteudo_Pagina $pagina = null,
                                         WeLearn_Usuarios_Usuario $usuario = null)
    {

    }

    private function _criarVariosFromCassandra(array $column,
                                             WeLearn_Cursos_Conteudo_Pagina $pagina = null,
                                             WeLearn_Usuarios_Usuario $usuario = null)
    {

    }
}
