<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 14:39
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Cursos_Area extends WeLearn_DTO_AbstractDTO
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
     * @param string $id
     * @param string $descricao
     */
    public function __construct($id = '', $descricao = '')
    {
        $dados = array(
            'id' => $id,
            'descricao' => $descricao
        );

        parent::__construct($dados);
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
     * @param string $descricao
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
            'descricao' => (string) $this->getDescricao()
        );
    }


}
