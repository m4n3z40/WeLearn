<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 13:51
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_InstantMessenger extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_descricaoIM;

    /**
     * @var string
     */
    private $_descricaoUsuarioIM;

    /**
     * @var string
     */
    private $_usuarioId;

    /**
     * @param array $dados
     */
    public function __construct(array $dados = null)
    {
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
     * @param string $descricaoIM
     */
    public function setDescricaoIM($descricaoIM)
    {
        $this->_descricaoIM = (string)$descricaoIM;
    }

    /**
     * @return string
     */
    public function getDescricaoIM()
    {
        return $this->_descricaoIM;
    }

    /**
     * @param string $descricaoUsuarioIM
     */
    public function setDescricaoUsuarioIM($descricaoUsuarioIM)
    {
        $this->_descricaoUsuarioIM = (string)$descricaoUsuarioIM;
    }

    /**
     * @return string
     */
    public function getDescricaoUsuarioIM()
    {
        return $this->_descricaoUsuarioIM;
    }

    /**
     * @param string $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->_usuarioId = (string)$usuarioId;
    }

    /**
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->_usuarioId;
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
            'descricaoIM' => $this->getDescricaoIM(),
            'descricaoUsuarioIM' => $this->getDescricaoUsuarioIM(),
            'usuarioId' => $this->getUsuarioId(),
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
            'id' => $this->getId(),
            'descricaoIM' => $this->getDescricaoIM(),
            'descricaoUsuarioIM' => $this->getDescricaoUsuarioIM(),
            'usuarioId' => $this->getUsuarioId()
        );
    }
}