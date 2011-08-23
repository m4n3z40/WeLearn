<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 16:52
 * 
 * Description:
 *
 */
 
class WeLearn_DAO_DAONaoEncontradaException extends WeLearn_Base_Exception
{
    /**
     * @var string
     */
    protected $message = 'A Dao requisitada não foi possível ser criada por que não
                          não foi encontrada nas include paths registradas, verifique
                          as include paths e o nome da DAO que está tentando criar.';

    /**
     * @var string
     */
    protected $DAO = '';

    /**
     * @param string $classeDAO
     * @param int $code
     * @param null $previous
     */
    public function __construct($classeDAO, $code = 0, Exception $previous = null)
    {
        $this->DAO = (string)$classeDAO;
        $message = $this->message . ' ( Classe DAO: ' . $this->DAO . ' )';
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param string $DAO
     * @return void
     */
    public function setDAO($DAO)
    {
        $this->DAO = (string)$DAO;
    }

    /**
     * @return string
     */
    public function getDAO()
    {
        return $this->DAO;
    }
}
