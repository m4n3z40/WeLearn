<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 16/03/12
 * Time: 16:51
 * To change this template use File | Settings | File Templates.
 */
class ImagemUsuarioDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'usuarios_imagem';

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $this->_cf->insert( $dto->getUsuarioId(), $dto->toCassandra() );

        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $this->_cf->insert( $dto->getUsuarioId(), $dto->toCassandra() );
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        return array();
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $column = $this->_cf->get($id);

        $imagemUsuario = $this->criarNovo();
        $imagemUsuario->fromCassandra($column);

        return $imagemUsuario;
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @return int
     */
    public function recuperarQtdTotal($de = null, $ate = null)
    {
        return 0;
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function remover($id)
    {
        $imagemUsuario = $this->recuperar($id);

        $this->_cf->remove($id);

        $imagemUsuario->setPersistido(false);

        return $imagemUsuario;
    }

    /**
     * @param array|null $dados
     * @return WeLearn_DTO_IDTO
     */
    public function criarNovo(array $dados = null)
    {
        $imagem = new WeLearn_Usuarios_ImagemUsuario();
        $imagem->preencherPropriedades($dados);

        return $imagem;
    }
}
