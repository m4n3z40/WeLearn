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
     * @var WeLearn_Usuarios_Usuario
     */
    private $_usuario;

    public function __construct($url = '', WeLearn_Usuarios_Usuario $usuario = null)
    {
        $dados = array(
            'url' => $url,
            'usuario' => $usuario
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
     * @param \WeLearn_Usuarios_Usuario $usuario
     */
    public function setUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        $this->_usuario = $usuario;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getUsuario()
    {
        return $this->_usuario;
    }
}
