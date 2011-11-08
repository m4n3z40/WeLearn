<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 23:48
 *
 * Description:
 *
 */

class WeLearn_Cursos_Avaliacoes_ControleAvaliacao extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_dataAplicacao;

    /**
     * @var float
     */
    private $_tempoDecorrido;

    /**
     * @var double
     */
    private $_nota;

    /**
     * @var int
     */
    private $_qtdTentativas;

    /**
     * @var WeLearn_Cursos_Avaliacoes_Avaliacao
     */
    private $_avaliacao;

    /**
     * @var WeLearn_Cursos_ParticipacaoCurso
     */
    private $_participacaoCurso;

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
    private $_respostas;

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
     * @param string $dataAplicacao
     */
    public function setDataAplicacao($dataAplicacao)
    {
        $this->_dataAplicacao = (string)$dataAplicacao;
    }

    /**
     * @return string
     */
    public function getDataAplicacao()
    {
        return $this->_dataAplicacao;
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
     * @param \double $nota
     */
    public function setNota($nota)
    {
        $this->_nota = (double)$nota;
    }

    /**
     * @return \double
     */
    public function getNota()
    {
        return $this->_nota;
    }

    /**
     * @param \WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     */
    public function setParticipacaoCurso(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $this->_participacaoCurso = $participacaoCurso;
    }

    /**
     * @return \WeLearn_Cursos_ParticipacaoCurso
     */
    public function getParticipacaoCurso()
    {
        return $this->_participacaoCurso;
    }

    /**
     * @param int $qtdTentativas
     */
    public function setQtdTentativas($qtdTentativas)
    {
        $this->_qtdTentativas = (int)$qtdTentativas;
    }

    /**
     * @return int
     */
    public function getQtdTentativas()
    {
        return $this->_qtdTentativas;
    }

    /**
     * @param array $respostas
     */
    public function setRespostas(array $respostas)
    {
        $this->_respostas = $respostas;
    }

    /**
     * @return array
     */
    public function getRespostas()
    {
        return $this->_respostas;
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
     * @param float $tempoDecorrido
     */
    public function setTempoDecorrido($tempoDecorrido)
    {
        $this->_tempoDecorrido = (float)$tempoDecorrido;
    }

    /**
     * @return float
     */
    public function getTempoDecorrido()
    {
        return $this->_tempoDecorrido;
    }

    /**
     * @return void
     */
    public function reprovar()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function verificarDisponibilidadeTentativas()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function bloquear()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function desativar()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function registrarInicio()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function registrarFim()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @param WeLearn_Cursos_Avaliacoes_RespostaAvaliacao $resposta
     * @return void
     */
    public function adicionarResposta(WeLearn_Cursos_Avaliacoes_RespostaAvaliacao $resposta)
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function calcularResultados()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function aprovar()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        $respostas = array();
        if (!is_null($this->_respostas)) {
            foreach ($this->getRespostas() as $resposta) {
                $respostas[] = $resposta->toArray();
            }
        }

        return array(
            'id' => $this->getId(),
            'dataAplicacao' => $this->getDataAplicacao(),
            'tempoDecorrido' => $this->getTempoDecorrido(),
            'nota' => $this->getNota(),
            'qtdTentativas' => $this->getQtdTentativas(),
            'avaliacao' => $this->getAvaliacao()->toArray(),
            'participacaoCurso' => $this->getParticipacaoCurso()->toArray(),
            'status' => $this->getStatus(),
            'situacao' => $this->getSituacao(),
            'respostas' => $resposta,
            'persistido' => $this->isPersistido()
        );
    }
}