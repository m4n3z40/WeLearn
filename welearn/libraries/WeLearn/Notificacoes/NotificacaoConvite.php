<?php

class WeLearn_Notificacoes_NotificacaoConvite extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Convites_ConviteCadastrado
     */
    private $_convite;

    /**
     * @param \WeLearn_Convites_ConviteCadastrado $convite
     */
    public function setConvite(WeLearn_Convites_ConviteCadastrado $convite)
    {
        $this->_convite = $convite;
    }

    /**
     * @return \WeLearn_Convites_ConviteCadastrado
     */
    public function getConvite()
    {
        return $this->_convite;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'convite' => $this->getConvite()->toArray()
            )
        );

        return $selfArray;
    }
}