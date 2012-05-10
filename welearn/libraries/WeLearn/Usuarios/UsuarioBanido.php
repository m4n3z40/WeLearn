<?php

/**
 * Create by Victor
 */
class WeLearn_Usuarios_UsuarioBanido extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_dataBanimento;

    /**
     * @var string
     */
    private $_dataInscricao;

    /**
     * @var string
     */
    private $_email;

    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_nome;

    /**
     * @var string
     */
    private $_sobrenome;

    /**
     * @param string $dataBanimento
     */
    public function setDataBanimento($dataBanimento)
    {
        $this->_dataBanimento = (string)$dataBanimento;
    }

    /**
     * @return string
     */
    public function getDataBanimento()
    {
        return $this->_dataBanimento;
    }

    /**
     * @param string $dataInscricao
     */
    public function setDataInscricao($dataInscricao)
    {
        $this->_dataInscricao = (string)$dataInscricao;
    }

    /**
     * @return string
     */
    public function getDataInscricao()
    {
        return $this->_dataInscricao;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->_email = (string)$email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = (string)$id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->_nome = (string)$nome;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->_nome;
    }

    /**
     * @param string $sobrenome
     */
    public function setSobrenome($sobrenome)
    {
        $this->_sobrenome = (string)$sobrenome;
    }

    /**
     * @return string
     */
    public function getSobrenome()
    {
        return $this->_sobrenome;
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'dataBanimento' => $this->getDataBanimento(),
            'dataInscricao' => $this->getDataInscricao(),
            'email' => $this->getEmail(),
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'sobrenome' => $this->getSobrenome(),
            'persistido' => $this->isPersistido()
        );
    }
}