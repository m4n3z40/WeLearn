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
class WeLearn_Usuarios_RedeSocial extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_descricaoRS;

    /**
     * @var string
     */
    private $_urlUsuarioRS;

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
     * @param string $descricaoRS
     */
    public function setDescricaoRS($descricaoRS)
    {
        $this->_descricaoRS = (string)$descricaoRS;
    }

    /**
     * @return string
     */
    public function getDescricaoRS()
    {
        return $this->_descricaoRS;
    }

    /**
     * @param string $urlUsuarioRS
     */
    public function setUrlUsuarioRS($urlUsuarioRS)
    {
        $this->_urlUsuarioRS = (string)$urlUsuarioRS;
    }

    /**
     * @return string
     */
    public function getUrlUsuarioRS()
    {
        return $this->_urlUsuarioRS;
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
            'descricaoRS' => $this->getDescricaoRS(),
            'urlUsuarioRS' => $this->getUrlUsuarioRS(),
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
            'descricaoRS' => $this->getDescricaoRS(),
            'urlUsuarioRS' => $this->getUrlUsuarioRS(),
            'usuarioId' => $this->getUsuarioId()
        );
    }
}