<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 14:04
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_DadosPessoaisUsuario extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_usuario;

    /**
     * @var string
     */
    private $_sexo;

    /**
     * @var string
     */
    private $_pais;

    /**
     * @var string
     */
    private $_cidade;

    /**
     * @var string
     */
    private $_endereco;

    /**
     * @var string
     */
    private $_dataNascimento;

    /**
     * @var string
     */
    private $_tel;

    /**
     * @var string
     */
    private $_telAlternativo;

    /**
     * @var string
     */
    private $_descricaoPessoal;

    /**
     * @var string
     */
    private $_homePage;

    /**
     * @var array
     */
    private $_listaDeIM;

    /**
     * @var array
     */
    private $_listaDeRS;

    /**
     * @param string $cidade
     */
    public function setCidade($cidade)
    {
        $this->_cidade = (string)$cidade;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->_cidade;
    }

    /**
     * @param string $dataNascimento
     */
    public function setDataNascimento($dataNascimento)
    {
        $this->_dataNascimento = (string)$dataNascimento;
    }

    /**
     * @return string
     */
    public function getDataNascimento()
    {
        return $this->_dataNascimento;
    }

    /**
     * @param string $descricaoPessoal
     */
    public function setDescricaoPessoal($descricaoPessoal)
    {
        $this->_descricaoPessoal = (string)$descricaoPessoal;
    }

    /**
     * @return string
     */
    public function getDescricaoPessoal()
    {
        return $this->_descricaoPessoal;
    }

    /**
     * @param string $endereco
     */
    public function setEndereco($endereco)
    {
        $this->_endereco = (string)$endereco;
    }

    /**
     * @return string
     */
    public function getEndereco()
    {
        return $this->_endereco;
    }

    /**
     * @param string $homePage
     */
    public function setHomePage($homePage)
    {
        $this->_homePage = (string)$homePage;
    }

    /**
     * @return string
     */
    public function getHomePage()
    {
        return $this->_homePage;
    }

    /**
     * @param array $listaDeIM
     */
    public function setListaDeIM(array $listaDeIM)
    {
        $this->_listaDeIM = $listaDeIM;
    }

    /**
     * @return array
     */
    public function getListaDeIM()
    {
        return $this->_listaDeIM;
    }

    /**
     * @param array $listaDeRS
     */
    public function setListaDeRS(array $listaDeRS)
    {
        $this->_listaDeRS = $listaDeRS;
    }

    /**
     * @return array
     */
    public function getListaDeRS()
    {
        return $this->_listaDeRS;
    }

    /**
     * @param string $pais
     */
    public function setPais($pais)
    {
        $this->_pais = (string)$pais;
    }

    /**
     * @return string
     */
    public function getPais()
    {
        return $this->_pais;
    }

    /**
     * @param string $sexo
     */
    public function setSexo($sexo)
    {
        $this->_sexo = (string)$sexo;
    }

    /**
     * @return string
     */
    public function getSexo()
    {
        return $this->_sexo;
    }

    /**
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->_tel = (string)$tel;
    }

    /**
     * @return string
     */
    public function getTel()
    {
        return $this->_tel;
    }

    /**
     * @param string $telAlternativo
     */
    public function setTelAlternativo($telAlternativo)
    {
        $this->_telAlternativo = (string)$telAlternativo;
    }

    /**
     * @return string
     */
    public function getTelAlternativo()
    {
        return $this->_telAlternativo;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $usuario
     */
    public function setUsuario(WeLearn_Usuarios_Usuario $usuario)
    {
        $this->_usuario = $usuario;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getUsuario()
    {
        return $this->_usuario;
    }
}
