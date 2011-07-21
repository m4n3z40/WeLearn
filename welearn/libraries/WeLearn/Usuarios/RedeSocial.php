<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 13:56
 * 
 * Description:
 *
 */

/**
 * 
 */
class WeLearn_Usuarios_RedeSocial extends WeLearn_DTO_AbstractDTO {
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @param int $id
     * @param string $descricao
     */
    public function __construct( $id = 0, $descricao = '' )
    {
        $dados = array(
            'id' => $id,
            'descricao' => $descricao
        );

        parent::__construct( $dados );
    }

    /**
     * @param string $descricao
     * @return void
     */
    public function setDescricao( $descricao )
    {
        $this->_descricao = (string) $descricao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->_descricao;
    }

    /**
     * @param int $id
     * @return void
     */
    public function setId( $id )
    {
        $this->_id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }
}
