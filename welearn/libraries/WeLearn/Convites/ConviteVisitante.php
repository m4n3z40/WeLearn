<?php
/**
 * Created by Thiago Monteiro
 * Date: 26/07/11
 * Time: 17:34
 *
 * Description:
 *
 */

class WeLearn_Convites_ConviteVisitante extends WeLearn_Convites_ConviteBasico
{
    /**
     * @var string
     **/
    protected $_email;

    /**
     * @var string
     **/
    protected $_nome;

    /**
     * @param string $email
     **/
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return string
     **/
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $nome
     **/
    public function setNome($nome)
    {
        $this->_nome = $nome;
    }

    /**
     * @return string
     **/
    public function getNome()
    {
        return $this->_nome;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'email' => $this->getEmail(),
                'nome' => $this->getNome()
            )
        );

        return $selfArray;
    }
}