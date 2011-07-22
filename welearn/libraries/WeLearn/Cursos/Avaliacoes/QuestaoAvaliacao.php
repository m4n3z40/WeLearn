<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 23:20
 *
 * Description:
 *
 */

class WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_enunciado;

    /**
     * @var int
     */
    private $_qtdAlternativas;

    /**
     * @var int
     */
    private $_qtdAlternativasExibir;

    /**
     * @var WeLearn_Cursos_Avaliacoes_Avaliacao
     */
    private $_avaliacao;

    /**
     * @var array
     */
    private $_alternativas;

    /**
     * @param array $alternativas
     */
    public function setAlternativas(array $alternativas)
    {
        $this->_alternativas = $alternativas;
    }

    /**
     * @return array
     */
    public function getAlternativas()
    {
        return $this->_alternativas;
    }

    /**
     * @param \WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao
     */
    public function setAvaliacao(WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao)
    {
        $this->_avaliacao = $avaliacao;
    }

    /**
     * @return \WeLearn_Cursos_Avaliacoes_Avaliacao
     */
    public function getAvaliacao()
    {
        return $this->_avaliacao;
    }

    /**
     * @param string $enunciado
     */
    public function setEnunciado($enunciado)
    {
        $this->_enunciado = (string)$enunciado;
    }

    /**
     * @return string
     */
    public function getEnunciado()
    {
        return $this->_enunciado;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $qtdAlternativas
     */
    public function setQtdAlternativas($qtdAlternativas)
    {
        $this->_qtdAlternativas = (int)$qtdAlternativas;
    }

    /**
     * @return int
     */
    public function getQtdAlternativas()
    {
        return $this->_qtdAlternativas;
    }

    /**
     * @param int $qtdAlternativasExibir
     */
    public function setQtdAlternativasExibir($qtdAlternativasExibir)
    {
        $this->_qtdAlternativasExibir = (int)$qtdAlternativasExibir;
    }

    /**
     * @return int
     */
    public function getQtdAlternativasExibir()
    {
        return $this->_qtdAlternativasExibir;
    }

    /**
     * @return void
     */
    public function recuperarAlternativas()
    {
        //@TODO: implementar este médodo!!
    }
}
