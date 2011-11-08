<?php
/**
 * Created by Allan Marques
 * Date: 29/07/11
 * Time: 03:02
 * 
 * Description:
 *
 */
 
class WeLearn_Usuarios_AutenticacaoLoginInvalidoException extends WeLearn_Base_Exception
{
    public function __construct($login, $code = 0, Exception $previous = null)
    {
        $msg = 'O login informado ("' . $login . '") não corresponde a nenhum usuário cadastrado no serviço. <br/>'
              .'Verifique o usuário e tente novamente.';
        parent::__construct($msg, $code, $previous);
    }
}
