<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 17/08/11
 * Time: 01:59
 * To change this template use File | Settings | File Templates.
 */

class WeLearn_Base_SessaoDesabilitadaException extends WeLearn_Base_Exception
{
    protected $message = 'A sessão do CI não está habilitada,
                          carregue a classe session do CI antes de utilizar este recurso';
}