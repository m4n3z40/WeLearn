<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 15:28
 * 
 * Description:
 *
 */
 
class WeLearn_Usuarios_ConfiguracaoUsuario extends WeLearn_DTO_AbstractDTO {
    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_usuario;

    /**
     * @var int
     */
    private $_privacidadePerfil;

    /**
     * @var int
     */
    private $_privacidadeMP;

    /**
     * @var int
     */
    private $_privacidadeConvites;

    /**
     * @var int
     */
    private $_privacidadeCompartilhamento;

    /**
     * @var int
     */
    private $_statusCompartilhamento;

    /**
     * @var int
     */
    private $_privacidadeNotificacoes;

    /**
     * @var array
     */
    private $_notificacoesHabilitadas;

    /**
     * @param int $privacidadeCompartilhamento
     */
    public function setPrivacidadeCompartilhamento( $privacidadeCompartilhamento )
    {
        $this->_privacidadeCompartilhamento = (int) $privacidadeCompartilhamento;
    }

    /**
     * @return int
     */
    public function getPrivacidadeCompartilhamento()
    {
        return $this->_privacidadeCompartilhamento;
    }

    /**
     * @param int $privacidadeConvites
     */
    public function setPrivacidadeConvites( $privacidadeConvites )
    {
        $this->_privacidadeConvites = (int) $privacidadeConvites;
    }

    /**
     * @return int
     */
    public function getPrivacidadeConvites()
    {
        return $this->_privacidadeConvites;
    }

    /**
     * @param int $privacidadeMP
     */
    public function setPrivacidadeMP( $privacidadeMP )
    {
        $this->_privacidadeMP = (int) $privacidadeMP;
    }

    /**
     * @return int
     */
    public function getPrivacidadeMP()
    {
        return $this->_privacidadeMP;
    }

    /**
     * @param int $privacidadeNotificacoes
     */
    public function setPrivacidadeNotificacoes( $privacidadeNotificacoes )
    {
        $this->_privacidadeNotificacoes = (int) $privacidadeNotificacoes;
    }

    /**
     * @return int
     */
    public function getPrivacidadeNotificacoes()
    {
        return $this->_privacidadeNotificacoes;
    }

    /**
     * @param int $privacidadePerfil
     */
    public function setPrivacidadePerfil( $privacidadePerfil )
    {
        $this->_privacidadePerfil = (int) $privacidadePerfil;
    }

    /**
     * @return int
     */
    public function getPrivacidadePerfil()
    {
        return $this->_privacidadePerfil;
    }

    /**
     * @param int $statusCompartilhamento
     */
    public function setStatusCompartilhamento( $statusCompartilhamento )
    {
        $this->_statusCompartilhamento = (int) $statusCompartilhamento;
    }

    /**
     * @return int
     */
    public function getStatusCompartilhamento()
    {
        return $this->_statusCompartilhamento;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $usuario
     */
    public function setUsuario( WeLearn_Usuarios_Usuario $usuario )
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

    /**
     * @param array $notificacoesHabilitadas
     */
    public function setNotificacoesHabilitadas( array $notificacoesHabilitadas )
    {
        $this->_notificacoesHabilitadas = $notificacoesHabilitadas;
    }

    /**
     * @return array
     */
    public function getNotificacoesHabilitadas()
    {
        return $this->_notificacoesHabilitadas;
    }
}
