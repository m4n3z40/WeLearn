<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 12/08/11
 * Time: 10:19
 * To change this template use File | Settings | File Templates.
 */
 
class AreaDAO extends WeLearn_DAO_AbstractDAO
{
    protected $_nomeCF = 'cursos_area';

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _adicionar(WeLearn_DTO_IDTO &$dto)
    {
        $dto->setId(urlencode($dto->getDescricao()));

        $this->_cf->insert($dto->getId(), $dto->toCassandra());
        $dto->setPersistido(true);
    }

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    protected function _atualizar(WeLearn_DTO_IDTO $dto)
    {
        $this->_cf->insert($dto->getId(), $dto->toCassandra());
    }

    /**
     * @param mixed $de
     * @param mixed $ate
     * @param array|null $filtros
     * @return array
     */
    public function recuperarTodos($de = null, $ate = null, array $filtros = null)
    {
        $count = (isset($filtros['count']) && is_int($filtros['count']))
                 ? $filtros['count']
                 : ColumnFamily::DEFAULT_ROW_COUNT;

        $encontrados = $this->_cf->get_range($de, $ate, $count);

        if ( ! empty($encontrados) ) {
            $listaAreas = array();

            foreach ($encontrados as $key => $columns) {
                $area = new WeLearn_Cursos_Area();
                $area->fromCassandra($columns);
                $area->setPersistido(true);

                $listaAreas[] = $area;
            }

            return $listaAreas;
        }

        return false;
    }

    /**
     * @param mixed $id
     * @return WeLearn_DTO_IDTO
     */
    public function recuperar($id)
    {
        $dados_area = $this->_cf->get($id);

        $area = new WeLearn_Cursos_Area();
        $area->fromCassandra($dados_area);
        $area->setPersistido(true);

        return $area;
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