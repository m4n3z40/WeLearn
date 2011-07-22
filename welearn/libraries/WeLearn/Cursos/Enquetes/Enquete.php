<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 14:26
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Enquetes_Enquete extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_questao;

    /**
     * @var int
     */
    private $_qtdAlternativas;

    /**
     * @var string
     */
    private $_dataCriacao;

    /**
     * @var string
     */
    private $_dataExpiracao;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var WeLearn_Usuarios_Moderador
     */
    private $_criador;

    /**
     * @var int
     */
    private $_status;

    /**
     * @var int
     */
    private $_situacao;

    /**
     * @var array
     */
    private $_alternativas;

    /**
     * @var int
     */
    private $_totalVotos;

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
     * @param \WeLearn_Usuarios_Moderador $criador
     */
    public function setCriador(WeLearn_Usuarios_Moderador $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_Moderador
     */
    public function getCriador()
    {
        return $this->_criador;
    }

    /**
     * @param \WeLearn_Cursos_Curso $curso
     */
    public function setCurso(WeLearn_Cursos_Curso $curso)
    {
        $this->_curso = $curso;
    }

    /**
     * @return \WeLearn_Cursos_Curso
     */
    public function getCurso()
    {
        return $this->_curso;
    }

    /**
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->_dataCriacao = (string)$dataCriacao;
    }

    /**
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->_dataCriacao;
    }

    /**
     * @param string $dataExpiracao
     */
    public function setDataExpiracao($dataExpiracao)
    {
        $this->_dataExpiracao = (string)$dataExpiracao;
    }

    /**
     * @return string
     */
    public function getDataExpiracao()
    {
        return $this->_dataExpiracao;
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
     * @param string $questao
     */
    public function setQuestao($questao)
    {
        $this->_questao = (string)$questao;
    }

    /**
     * @return string
     */
    public function getQuestao()
    {
        return $this->_questao;
    }

    /**
     * @param int $situacao
     */
    public function setSituacao($situacao)
    {
        $this->_situacao = (int)$situacao;
    }

    /**
     * @return int
     */
    public function getSituacao()
    {
        return $this->_situacao;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_status = (int)$status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @return int
     */
    public function getTotalVotos()
    {
        return $this->_totalVotos;
    }

    public function recuperarAlternativas()
    {
        //@TODO: Implementar este método!!
    }

    public function adicionarAlternativa(WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa)
    {
        //@TODO: Implementar este método!!
    }

    public function alterarSituacao()
    {
        //@TODO: Implementar este método!!
    }

    public function alterarStatus()
    {
        //@TODO: Implementar este método!!
    }

    public function recuperarQtdTotalVotos()
    {
        //@TODO: Implementar este método!!
    }

    public function zerarVotos()
    {
        //@TODO: Implementar este método!!
    }

    public function recuperarQtdParcialVotos()
    {
        //@TODO: Implementar este método!!
    }
}