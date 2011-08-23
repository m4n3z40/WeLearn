<?php
/**
 * Created by Allan Marques
 * Date: 25/07/11
 * Time: 21:07
 * 
 * Description:
 *
 */
 
require_once LIBSPATH.'WeLearn/Base/Exception.php';

class WeLearn_Base_ParametroInvalidoException extends WeLearn_Base_Exception
{
    protected $message = 'O parâmetro informado é inválido, verifique o tipo esperado do parâmetro.';
}
