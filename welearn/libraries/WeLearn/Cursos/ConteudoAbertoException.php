<?php
/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 30/09/11
 * Time: 10:40
 * To change this template use File | Settings | File Templates.
 */
 
class WeLearn_Cursos_ConteudoAbertoException extends WeLearn_Base_Exception {
    public function __construct($msg = '', $code = 0, Exception $previous = null)
    {
        if ($msg == '') {
            $msg = 'Esta ação não é permitida enquanto o conteúdo do curso esteja aberto.';
        }
        parent::__construct($msg, $code, $previous);
    }
}
