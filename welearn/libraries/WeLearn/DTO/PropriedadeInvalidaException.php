<?php
/**
 * Created by Allan Marques
 * Date: 29/07/11
 * Time: 03:02
 * 
 * Description:
 *
 */
 
class WeLearn_DTO_PropriedadeInvalidaException extends WeLearn_Base_Exception
{
    public function __construct($propriedade, $code = 0, Exception $previous = null)
    {
        $msg = 'A propriedade "' . $propriedade . '" é inválida no objeto que está tentando acessá-la.';
        parent::__construct($msg, $code, $previous);
    }
}
