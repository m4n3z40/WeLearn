<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 19:33
 *
 * Description:
 *
 */

class WeLearn_Cursos_ParticipacaoCurso extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_dataInscricao;

    /**
     * @var float
     */
    private $_frequenciaTotal;

    /**
     * @var string
     */
    private $_dataUltimoAcesso;

    /**
     * @var double
     */
    private $_crFinal;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var WeLearn_Usuarios_Aluno
     */
    private $_aluno;

    /**
     * @var WeLearn_Cursos_Certificado
     */
    private $_certificado;

    /**
     * @var int
     */
    private $_situacao;

    /**
     * @var WeLearn_Cursos_Conteudo_Pagina
     */
    private $_paginaAtual;

    /**
     * @param \WeLearn_Usuarios_Aluno $aluno
     */
    public function setAluno(WeLearn_Usuarios_Aluno $aluno)
    {
        $this->_aluno = $aluno;
    }

    /**
     * @return \WeLearn_Usuarios_Aluno
     */
    public function getAluno()
    {
        return $this->_aluno;
    }

    /**
     * @param \WeLearn_Cursos_Certificado $certificado
     */
    public function setCertificado(WeLearn_Cursos_Certificado $certificado)
    {
        $this->_certificado = $certificado;
    }

    /**
     * @return \WeLearn_Cursos_Certificado
     */
    public function getCertificado()
    {
        return $this->_certificado;
    }

    /**
     * @param \double $crFinal
     */
    public function setCrFinal($crFinal)
    {
        $this->_crFinal = (double)$crFinal;
    }

    /**
     * @return \double
     */
    public function getCrFinal()
    {
        return $this->_crFinal;
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
     * @param string $dataUltimoAcesso
     */
    public function setDataUltimoAcesso($dataUltimoAcesso)
    {
        $this->_dataUltimoAcesso = (string)$dataUltimoAcesso;
    }

    /**
     * @return string
     */
    public function getDataUltimoAcesso()
    {
        return $this->_dataUltimoAcesso;
    }

    /**
     * @param float $frequenciaTotal
     */
    public function setFrequenciaTotal($frequenciaTotal)
    {
        $this->_frequenciaTotal = (float)$frequenciaTotal;
    }

    /**
     * @return float
     */
    public function getFrequenciaTotal()
    {
        return $this->_frequenciaTotal;
    }

    /**
     * @param \WeLearn_Cursos_Conteudo_Pagina $paginaAtual
     */
    public function setPaginaAtual(WeLearn_Cursos_Conteudo_Pagina $paginaAtual)
    {
        $this->_paginaAtual = $paginaAtual;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Pagina
     */
    public function getPaginaAtual()
    {
        return $this->_paginaAtual;
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
     * @return void
     */
    public function aceitarInscricao()
    {
        //@TODO: implementar este médodo!!
    }

    /**
     * @return void
     */
    public function concluirCurso()
    {
        //@TODO: implementar este médodo!!
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $curso
     * @return void
     */
    public function inscrever(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $curso)
    {
        //@TODO: implementar este médodo!!
    }

    /**
     * @return void
     */
    public function recusarInscricao()
    {
        //@TODO: implementar este médodo!!
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
            'dataInscricao' => $this->getDataInscricao(),
            'frequenciaTotal' => $this->getFrequenciaTotal(),
            'dataUltimoAcesso' => $this->getDataUltimoAcesso(),
            'crFinal' => $this->getCrFinal(),
            'curso' => $this->getCurso()->toArray(),
            'aluno' => $this->getAluno()->toArray(),
            'certificado' => (is_null($this->_certificado)) ? '' : $this->getCertificado()->toArray(),
            'situacao' => $this->getSituacao(),
            'paginaAtual' => (is_null($this->_paginaAtual)) ? '' : $this->getPaginaAtual()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
