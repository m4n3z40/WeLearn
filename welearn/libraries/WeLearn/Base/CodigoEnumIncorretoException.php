<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 03:59
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Base_CodigoEnumIncorretoException extends WeLearn_Base_Exception
{
    protected $message = 'O código do Enum informado está incorreto ou não existe no Enum atual, verifique.';
}
