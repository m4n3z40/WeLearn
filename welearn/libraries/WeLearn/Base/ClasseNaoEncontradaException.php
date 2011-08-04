<?php
/**
 * Created by Allan Marques
 * Date: 25/07/11
 * Time: 22:11
 * 
 * Description:
 *
 */

require_once LIBSPATH.'WeLearn/Base/Exception.php';

class WeLearn_Base_ClasseNaoEncontradaException extends WeLearn_Base_Exception
{
    /**
     * @var string
     */
    protected $message = 'A classe requisitada não foi encontrada,
                          verifique o caminho do arquivo e se o arquivo
                          possui mesmo a classe referenciada.';

    /**
     * @var string
     */
    protected $classe = '';

    /**
     * @param string $classe
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($classe, $code = 0, Exception $previous = null)
    {
        $this->classe = $classe;
        $message = $this->message.' ( Classe: ' . $this->classe . ' )';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }
}