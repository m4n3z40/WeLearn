<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 03:57
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Base_Exception extends Exception
{

    /**
     * @param string $msg
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($msg = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($msg, (int)$code, $previous);
    }
}
