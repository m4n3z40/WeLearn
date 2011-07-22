<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:51
 *
 * Description:
 *
 */

class WeLearn_Cursos_Curso extends WeLearn_Cursos_CursoBasico
{

    /**
     * @var string
     */
    private $_objetivos;

    /**
     * @var string
     */
    private $_conteudoProposto;

    /**
     * @var float
     */
    private $_tempoDuracaoMax;

    /**
     * @var WeLearn_Usuarios_GerenciadorPrincipal
     */
    private $_criador;

    /**
     * @var WeLearn_Cursos_ImagemCurso
     */
    private $_imagem;

    /**
     * @var WeLearn_Cursos_ConfiguracaoCurso
     */
    private $_configuracao;

    /**
     * @param \WeLearn_Cursos_ConfiguracaoCurso $configuracao
     */
    public function setConfiguracao(WeLearn_Cursos_ConfiguracaoCurso $configuracao)
    {
        $this->_configuracao = $configuracao;
    }

    /**
     * @return \WeLearn_Cursos_ConfiguracaoCurso
     */
    public function getConfiguracao()
    {
        return $this->_configuracao;
    }

    /**
     * @param string $conteudoProposto
     */
    public function setConteudoProposto($conteudoProposto)
    {
        $this->_conteudoProposto = (string)$conteudoProposto;
    }

    /**
     * @return string
     */
    public function getConteudoProposto()
    {
        return $this->_conteudoProposto;
    }

    /**
     * @param \WeLearn_Usuarios_GerenciadorPrincipal $criador
     */
    public function setCriador(WeLearn_Usuarios_GerenciadorPrincipal $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_GerenciadorPrincipal
     */
    public function getCriador()
    {
        return $this->_criador;
    }

    /**
     * @param \WeLearn_Cursos_ImagemCurso $imagem
     */
    public function setImagem(WeLearn_Cursos_ImagemCurso $imagem)
    {
        $this->_imagem = $imagem;
    }

    /**
     * @return \WeLearn_Cursos_ImagemCurso
     */
    public function getImagem()
    {
        return $this->_imagem;
    }

    /**
     * @param string $objetivos
     */
    public function setObjetivos($objetivos)
    {
        $this->_objetivos = (string)$objetivos;
    }

    /**
     * @return string
     */
    public function getObjetivos()
    {
        return $this->_objetivos;
    }

    /**
     * @param float $tempoDuracaoMax
     */
    public function setTempoDuracaoMax($tempoDuracaoMax)
    {
        $this->_tempoDuracaoMax = (float)$tempoDuracaoMax;
    }

    /**
     * @return float
     */
    public function getTempoDuracaoMax()
    {
        return $this->_tempoDuracaoMax;
    }

    /**
     * @param array $opcoes
     * @return void
     */
    public function alterarOpcoesPrivacidade(array $opcoes)
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @param array $opcoes
     * @return void
     */
    public function alterarOpcoesEnquete(array $opcoes)
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @param array $opcoes
     * @return void
     */
    public function alterarOpcoesForum(array $opcoes)
    {
        //@TODO: Implementar este método!!
    }
}
