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
class WeLearn_Usuarios_ListaDeRS extends  WeLearn_DTO_AbstractDTO {
    /**
     * @var string
     */
    private $_linkRS;

    /**
     * @var WeLearn_Usuarios_RedeSocial
     */
    private $_redeSocial;

    /**
     * @var WeLearn_Usuarios_DadosPessoaisUsuario
     */
    private $_dadosPessoais;

    /**
     * @param string $linkRS
     * @param null|WeLearn_Usuarios_RedeSocial $redeSocial
     * @param null|WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais
     */
    public function __construct( $linkRS = '',
                                 WeLearn_Usuarios_RedeSocial $redeSocial = null,
                                 WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais = null )
    {
        $dados = array(
            'linkRS' => $linkRS,
            'redeSocial' => $redeSocial,
            'dadosPessoais' => $dadosPessoais
        );

        parent::__construct( $dados );
    }

    /**
     * @param \WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais
     */
    public function setDadosPessoais( WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais )
    {
        $this->_dadosPessoais = $dadosPessoais;
    }

    /**
     * @return \WeLearn_Usuarios_DadosPessoaisUsuario
     */
    public function getDadosPessoais()
    {
        return $this->_dadosPessoais;
    }

    /**
     * @param string $linkRS
     */
    public function setLinkRS( $linkRS )
    {
        $this->_linkRS = (string) $linkRS;
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
    public function setRedeSocial( WeLearn_Usuarios_RedeSocial $redeSocial )
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
}
