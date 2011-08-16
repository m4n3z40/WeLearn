<?php
/**
 * Created by Allan Marques
 * Date: 29/07/11
 * Time: 03:02
 * 
 * Description:
 *
 */
 
class WeLearn_Usuarios_AutenticacaoSenhaInvalidaException extends WeLearn_Base_Exception
{
    private $_senha;

    public function __construct($senha, $code = 0, Exception $previous = null)
    {
        $this->_senha = $senha;
        $msg = 'A senha informada é inválida.';
        parent::__construct($msg, $code, $previous);
    }

    public function getSenha()
    {
        return $this->_senha;
    }
}
