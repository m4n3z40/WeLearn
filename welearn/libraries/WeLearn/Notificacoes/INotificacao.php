<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/5/12
 * Time: 12:13 AM
 * To change this template use File | Settings | File Templates.
 */
interface WeLearn_Notificacoes_INotificacao
{
    /**
     * @abstract
     * @param WeLearn_Notificacoes_INotificador $notificador
     */
    public function adicionarNotificador(WeLearn_Notificacoes_INotificador $notificador);

    /**
     * @abstract
     * @param WeLearn_Notificacoes_INotificador $notificador
     */
    public function removerNotificador(WeLearn_Notificacoes_INotificador $notificador);

    /**
     * @abstract
     */
    public function notificar();
}
