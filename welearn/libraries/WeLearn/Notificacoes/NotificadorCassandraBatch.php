<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/5/12
 * Time: 12:51 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificadorCassandraBatch extends WeLearn_Notificacoes_NotificadorCassandra
{
    /**
     * @var SplObjectStorage
     */
    private $_notificacoes;

    public function __construct()
    {
        parent::__construct();

        $this->_notificacoes = new SplObjectStorage();
    }

    /**
     * @param WeLearn_Notificacoes_Notificacao $notificacao
     */
    public function notificar(WeLearn_Notificacoes_Notificacao $notificacao)
    {
        $this->_notificacoes->attach( $notificacao );
    }

    /**
     * Envia todas as notificações registradas ao destruir este objeto.
     */
    public function __destruct()
    {
        $this->_notificacaoDao->adicionarVarios( $this->_notificacoes );
    }
}
