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
     * @var int
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
     * @param int $id
     * @param string $descricao
     * @param null|WeLearn_Cursos_Area $area
     */
    public function __construct($id = 0, $descricao = '', WeLearn_Cursos_Area $area = null)
    {
        $dados = array(
            'id' => $id,
            'descricao' => $descricao,
            'area' => $area
        );

        parent::__construct($dados);
    }

    /**
     * @param $id
     * @return void
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * @return int
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
}
