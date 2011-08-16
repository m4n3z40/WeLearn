<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 14:40
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Cursos_Segmento extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @var WeLearn_Cursos_Area
     */
    private $_area;

    /**
     * @param string $id
     * @param string $descricao
     * @param null|WeLearn_Cursos_Area $area
     */
    public function __construct($id = '', $descricao = '', WeLearn_Cursos_Area $area = null)
    {
        $dados = array(
            'id' => $id,
            'descricao' => $descricao,
        );

        parent::__construct($dados);

        if ( !is_null($area) ) {
            $this->setArea($area);
        }
    }

    /**
     * @param string $id
     * @return void
     */
    public function setId($id)
    {
        $this->_id = (string)$id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param $descricao
     * @return void
     */
    public function setDescricao($descricao)
    {
        $this->_descricao = (string)$descricao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->_descricao;
    }

    /**
     * @param WeLearn_Cursos_Area $area
     * @return void
     */
    public function setArea(WeLearn_Cursos_Area $area)
    {
        $this->_area = $area;
    }

    /**
     * @return null|WeLearn_Cursos_Area
     */
    public function getArea()
    {
        return $this->_area;
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'descricao' => $this->getDescricao(),
            'area' => $this->getArea()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }

    /**
     * Converte os dados das propriedades do objeto em um array para ser persistido no BD Cassandra
     *
     * @return array
     */
    public function toCassandra()
    {
        return array(
            'id' => (string) $this->getId(),
            'descricao' => (string) $this->getDescricao(),
            'area' => (string) $this->getArea()->getId()
        );
    }
}
