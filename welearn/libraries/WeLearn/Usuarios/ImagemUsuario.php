<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 15:10
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_ImagemUsuario extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_url;

    /**
     * @var string
     */
    private $_usuarioId;

    public function __construct($url = '', $usuarioId = '')
    {
        $dados = array(
            'url' => $url,
            'usuarioId' => $usuarioId
        );

        parent::__construct($dados);
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = (string)$url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
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
            'url' => $this->getUrl(),
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
            'url' => $this->getUrl(),
            'usuarioId' => $this->getUsuarioId()
        );
    }
}
