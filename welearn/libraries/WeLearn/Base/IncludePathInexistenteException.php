<?php
/**
 * Created by Allan Marques
 * Date: 25/07/11
 * Time: 20:47
 * 
 * Description:
 *
 */

require_once LIBSPATH.'WeLearn/Base/Exception.php';

class WeLearn_Base_IncludePathInexistenteException extends WeLearn_Base_Exception
{
    /**
     * @var string
     */
    protected $message = 'A include path informada não existe, ainda não foi registrada ou a chave está incorreta.';

    /**
     * @var string
     */
    protected $includePath = '';

    /**
     * @param int|string $includePath
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($includePath, $code = 0, Exception $previous = null)
    {
        $this->includePath = $includePath;
        $message = $this->message . '( IncludePath: ' . $this->includePath . ' )';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getIncludePath()
    {
        return $this->includePath;
    }
}