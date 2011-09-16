<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 22:08
 * 
 * Description:
 *
 */
 
class WeLearn_DAO_CFNaoDefinidaException extends  WeLearn_DAO_DAONaoEncontradaException
{
    /**
     * @var string
     */
    protected $message = 'A DAO requisitada não pode ser criada por que sua column family não foi definida.
                        Defina a column family desta DAO e tente novamente.';

    /**
     * @param string $classeDAO
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($classeDAO, $code = 0, Exception $previous = null)
    {
        parent::__construct($classeDAO, $code, $previous);
    }
}
