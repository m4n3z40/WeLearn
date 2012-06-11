<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoRequisicaoAmizade extends WeLearn_Notificacoes_Notificacao
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

    public function getMsg()
    {
        if ( null === $this->_msg ) {

            $linkRemetente = anchor(
                '/perfil/' . $this->getConvite()->getRemetente()->getId(),
                $this->getConvite()->getRemetente()->getNome()
            );

            $linkConvites = anchor('/convite/index/recebidos', 'solicitação de amizade');

            $this->setMsg(
                $linkRemetente . ' enviou uma ' . $linkConvites . ' para você.'
            );
        }

        return parent::getMsg();
    }

    public function getUrl()
    {
        if ( null === $this->_url ) {

            $this->setUrl( site_url('/convite/index/recebidos') );

        }

        return parent::getUrl();
    }
}
