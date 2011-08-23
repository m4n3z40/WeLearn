<?php

/**
 * Create by Victor
 */
class WeLearn_Administracao_Administrador extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_dataCadastro;

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
    private $_login;

    /**
     * @var string
     */
    private $_nome;

    /**
     * @var string
     */
    private $_senha;

    /**
     * @param string $dataCadastro
     */
    public function setDataCadastro($dataCadastro)
    {
        $this->_dataCadastro = (string) $dataCadastro;
    }

    /**
     * @return string
     */
    public function getDataCadastro()
    {
        return $this->_dataCadastro;
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
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->_login = (string)$login;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->_login;
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
     * @param string $senha
     */
    public function setSenha($senha)
    {
        $this->_senha = (string)$senha;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->_senha;
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
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'nome' => $this->getNome(),
            'login' => $this->getLogin(),
            'senha' => $this->getSenha(),
            'dataCadastro' => $this->getDataCadastro(),
            'persistido' => $this->isPersistido()
        );
    }
}