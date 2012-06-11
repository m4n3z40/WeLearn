<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoCompartilhamentoPerfil extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Compartilhamento_Feed
     */
    private $_compartilhamento;

    /**
     * @param \WeLearn_Compartilhamento_Feed $compartilhamento
     */
    public function setCompartilhamento(WeLearn_Compartilhamento_Feed $compartilhamento)
    {
        $this->_compartilhamento = $compartilhamento;
    }

    /**
     * @return \WeLearn_Compartilhamento_Feed
     */
    public function getCompartilhamento()
    {
        return $this->_compartilhamento;
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
