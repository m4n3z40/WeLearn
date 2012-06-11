<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoResenhaRespondida extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Cursos_Reviews_Resenha
     */
    private $_resenha;

    /**
     * @param \WeLearn_Cursos_Reviews_Resenha $resenha
     */
    public function setResenha(WeLearn_Cursos_Reviews_Resenha $resenha)
    {
        $this->_resenha = $resenha;
    }

    /**
     * @return \WeLearn_Cursos_Reviews_Resenha
     */
    public function getResenha()
    {
        return $this->_resenha;
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
