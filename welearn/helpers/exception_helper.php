<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 16/08/11
 * Time: 09:22
 * To change this template use File | Settings | File Templates.
 */
 
function create_exception_description(Exception $e)
{
    return '(Exception: ' . get_class($e) . '): \n'
          .'Mensagem: ' . $e->getMessage() . '\n'
          .'No arquivo "' . $e->getFile() . '", linha ' . $e->getLine() . '.\n'
          .'Stacktrace: ' . $e->getTraceAsString();
}