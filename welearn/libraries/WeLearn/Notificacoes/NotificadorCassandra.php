<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/5/12
 * Time: 12:50 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificadorCassandra implements WeLearn_Notificacoes_INotificador
{
    /**
     * @var NotificacaoDAO
     */
    protected $_notificacaoDao;

    public function __construct()
    {
        $this->_notificacaoDao = WeLearn_DAO_DAOFactory::create('NotificacaoDAO');
    }

    /**
     * @param WeLearn_Notificacoes_Notificacao $notificacao
     */
    public function notificar(WeLearn_Notificacoes_Notificacao $notificacao)
    {
        // TODO: Implement notificar() method.
    }

}
