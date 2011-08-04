<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 17:16
 * 
 * Description:
 *
 */
 
class WeLearn_DAO_DAOInvalidaException extends WeLearn_DAO_DAONaoEncontradaException
{
    /**
     * @var string
     */
    protected $message = 'A DAO requisitada não pode ser criada por que não extende a classe
                          "WeLearn_DAO_AbstractDAO", verifique.';

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
