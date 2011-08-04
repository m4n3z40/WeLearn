<?php
/**
 * Created by Allan Marques
 * Date: 25/07/11
 * Time: 23:42
 * 
 * Description:
 *
 */

require_once LIBSPATH.'WeLearn/Base/Exception.php';

class WeLearn_Base_LoaderNaoIniciadoException extends WeLearn_Base_Exception
{
    protected $message = 'O Class Loader não foi iniciado, verifique a implementação do autoloader.';
}