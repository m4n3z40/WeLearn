<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 15:14
 * 
 * Description:
 *
 */

/**
 * 
 */
class WeLearn_Usuarios_Usuario extends WeLearn_DTO_AbstractDTO {
    /**
     * @var int
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
     * @var string
     */
    private $_email;

    /**
     * @var string
     */
    private $_senha;

    /**
     * @var string
     */
    private $_dataCadastro;

    /**
     * @var WeLearn_Usuarios_ImagemUsuario
     */
    private $_imagem;

    /**
     * @var WeLearn_Usuarios_DadosPessoaisUsuario
     */
    private $_dadosPessoais;

    /**
     * @var WeLearn_Usuarios_DadosProfissionaisUsuario
     */
    private $_dadosProfissionais;

    /**
     * @var WeLearn_Cursos_Segmento
     */
    private $_segmentoInteresse;

    /**
     * @var WeLearn_Usuarios_ConfiguracaoUsuario
     */
    private $_configuracao;

    /**
     * @param \WeLearn_Usuarios_ConfiguracaoUsuario $configuracao
     */
    public function setConfiguracao( WeLearn_Usuarios_ConfiguracaoUsuario $configuracao )
    {
        $this->_configuracao = $configuracao;
    }

    /**
     * @return \WeLearn_Usuarios_ConfiguracaoUsuario
     */
    public function getConfiguracao()
    {
        return $this->_configuracao;
    }

    /**
     * @param \WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais
     */
    public function setDadosPessoais( WeLearn_Usuarios_DadosPessoaisUsuario $dadosPessoais )
    {
        $this->_dadosPessoais = $dadosPessoais;
    }

    /**
     * @return \WeLearn_Usuarios_DadosPessoaisUsuario
     */
    public function getDadosPessoais()
    {
        return $this->_dadosPessoais;
    }

    /**
     * @param \WeLearn_Usuarios_DadosProfissionaisUsuario $dadosProfissionais
     */
    public function setDadosProfissionais( WeLearn_Usuarios_DadosProfissionaisUsuario $dadosProfissionais )
    {
        $this->_dadosProfissionais = $dadosProfissionais;
    }

    /**
     * @return \WeLearn_Usuarios_DadosProfissionaisUsuario
     */
    public function getDadosProfissionais()
    {
        return $this->_dadosProfissionais;
    }

    /**
     * @param string $dataCadastro
     */
    public function setDataCadastro( $dataCadastro )
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
    public function setEmail( $email )
    {
        $this->_email = (string) $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param int $id
     */
    public function setId( $id )
    {
        $this->_id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param \WeLearn_Usuarios_ImagemUsuario $imagem
     */
    public function setImagem( WeLearn_Usuarios_ImagemUsuario $imagem )
    {
        $this->_imagem = $imagem;
    }

    /**
     * @return \WeLearn_Usuarios_ImagemUsuario
     */
    public function getImagem()
    {
        return $this->_imagem;
    }

    /**
     * @param string $nome
     */
    public function setNome( $nome )
    {
        $this->_nome = (string) $nome;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->_nome;
    }

    /**
     * @param \WeLearn_Cursos_Segmento $segmentoInteresse
     */
    public function setSegmentoInteresse( WeLearn_Cursos_Segmento $segmentoInteresse )
    {
        $this->_segmentoInteresse = $segmentoInteresse;
    }

    /**
     * @return \WeLearn_Cursos_Segmento
     */
    public function getSegmentoInteresse()
    {
        return $this->_segmentoInteresse;
    }

    /**
     * @param string $senha
     */
    public function setSenha( $senha )
    {
        $this->_senha = (string) $senha;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->_senha;
    }

    /**
     * @param string $sobrenome
     */
    public function setSobrenome( $sobrenome )
    {
        $this->_sobrenome = (string) $sobrenome;
    }

    /**
     * @return string
     */
    public function getSobrenome()
    {
        return $this->_sobrenome;
    }
}