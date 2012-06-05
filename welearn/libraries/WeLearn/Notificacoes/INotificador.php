<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/5/12
 * Time: 12:15 AM
 * To change this template use File | Settings | File Templates.
 */
interface WeLearn_Notificacoes_INotificador
{
    /**
     * @abstract
     * @param WeLearn_Notificacoes_Notificacao $notificacao
     */
    public function notificar(WeLearn_Notificacoes_Notificacao $notificacao);
}
