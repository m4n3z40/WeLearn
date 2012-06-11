<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoRequisicaoAmizadeAceita extends WeLearn_Notificacoes_Notificacao
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
        //TODO: Implementar msg de notificação.
        return parent::getMsg();
    }

    public function getUrl()
    {
        //TODO: Implementar url da notificacao.
        return parent::getUrl();
    }
}
