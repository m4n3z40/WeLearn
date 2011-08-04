<?php


class WeLearn_Notificacoes_NotificacaoUsuario extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_destinatario;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_remetente;

    /**
     * @param \WeLearn_Usuarios_Usuario $destinatario
     */
    public function setDestinatario(WeLearn_Usuarios_Usuario $destinatario)
    {
        $this->_destinatario = $destinatario;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getDestinatario()
    {
        return $this->_destinatario;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $remetente
     */
    public function setRemetente(WeLearn_Usuarios_Usuario $remetente)
    {
        $this->_remetente = $remetente;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getRemetente()
    {
        return $this->_remetente;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'destinatario' => $this->getDestinatario()->toArray(),
                'remetente' => $this->getRemetente()->toArray()
            )
        );

        return $selfArray;
    }
}