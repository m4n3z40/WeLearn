<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 14:37
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_ListaDeRS extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_linkRS;

    /**
     * @var WeLearn_Usuarios_RedeSocial
     */
    private $_redeSocial;

    /**
     * @var string
     */
    private $_usuarioId;

    /**
     * @param string $linkRS
     * @param null|WeLearn_Usuarios_RedeSocial $redeSocial
     * @param string $usuarioId
     */
    public function __construct($linkRS = '',
        WeLearn_Usuarios_RedeSocial $redeSocial = null,
        $usuarioId = '')
    {
        $dados = array(
            'linkRS' => $linkRS,
            'redeSocial' => $redeSocial,
            'usuarioId' => $usuarioId
        );

        parent::__construct($dados);
    }

    /**
     * @param string $linkRS
     */
    public function setLinkRS($linkRS)
    {
        $this->_linkRS = (string)$linkRS;
    }

    /**
     * @return string
     */
    public function getLinkRS()
    {
        return $this->_linkRS;
    }

    /**
     * @param \WeLearn_Usuarios_RedeSocial $redeSocial
     */
    public function setRedeSocial(WeLearn_Usuarios_RedeSocial $redeSocial)
    {
        $this->_redeSocial = $redeSocial;
    }

    /**
     * @return \WeLearn_Usuarios_RedeSocial
     */
    public function getRedeSocial()
    {
        return $this->_redeSocial;
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
            'linkRS' => $this->getLinkRS(),
            'redeSocial' => $this->getRedeSocial()->toArray(),
            'usuarioId' => $this->getUsuarioId(),
            'persistido' => $this->isPersistido()
        );
    }


}
