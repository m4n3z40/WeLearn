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
     * @var int
     */
    private $_dataAplicacao;

    /**
     * @var float
     */
    private $_tempoDecorrido;

    /**
     * @var float
     */
    private $_nota = 0;

    /**
     * @var int
     */
    private $_qtdTentativas = 0;

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
    private $_status = WeLearn_Cursos_Avaliacoes_StatusAvaliacao::LIBERADA;

    /**
     * @var int
     */
    private $_situacao = WeLearn_Cursos_Avaliacoes_SituacaoAvaliacao::NAO_INICIADA;

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
     * @param int $dataAplicacao
     */
    public function setDataAplicacao($dataAplicacao)
    {
        $this->_dataAplicacao = (int)$dataAplicacao;
    }

    /**
     * @return string
     */
    public function getDataAplicacao()
    {
        return $this->_dataAplicacao;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if ( null === $this->_id ) {

            $this->_id = $this->getCFKey();

        }

        return $this->_id;
    }

    /**
     * @param float $nota
     */
    public function setNota($nota)
    {
        $this->_nota = (float)$nota;
    }

    /**
     * @return float
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
        $this->setSituacao( WeLearn_Cursos_Avaliacoes_SituacaoAvaliacao::REPROVADO );
    }

    /**
     * @return void
     */
    public function aprovar()
    {
        $this->setSituacao( WeLearn_Cursos_Avaliacoes_SituacaoAvaliacao::APROVADO );
    }

    /**
     * @return bool
     */
    public function verificarDisponibilidadeTentativas()
    {
        return $this->getQtdTentativas() < $this->getAvaliacao()->getQtdTentativasPermitidas();
    }

    /**
     * @return void
     */
    public function liberar()
    {
        $this->setStatus( WeLearn_Cursos_Avaliacoes_StatusAvaliacao::LIBERADA );
    }

    /**
     * @return void
     */
    public function bloquear()
    {
        $this->setStatus( WeLearn_Cursos_Avaliacoes_StatusAvaliacao::BLOQUEADA );
    }

    /**
     * @return void
     */
    public function desativar()
    {
        $this->setStatus( WeLearn_Cursos_Avaliacoes_StatusAvaliacao::DESATIVADA );
    }

    /**
     * @return void
     */
    public function finalizar()
    {
        $this->setStatus( WeLearn_Cursos_Avaliacoes_StatusAvaliacao::FINALIZADA );
    }

    /**
     * @param WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $resposta
     * @return void
     */
    public function adicionarResposta(WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $resposta)
    {
        $this->_respostas[] = $resposta;
    }

    /**
     * @return int
     */
    public function calcularResultados()
    {
        $qtdCorretas = 0;
        $qtdTotalQuestoes = count( $this->_respostas );

        for ($i = 0; $i < $qtdTotalQuestoes; $i++) {

            if ( $this->_respostas[$i]->isCorreta() ) {

                $qtdCorretas++;

            }

        }

        $nota = round( ($qtdCorretas / $qtdTotalQuestoes) * 10, 1 );

        $this->setNota( $nota );

        if ( $nota >= $this->getAvaliacao()->getNotaMinima() ) {

            $this->aprovar();
            $this->finalizar();

        } else {

            $this->reprovar();

            $this->_qtdTentativas++;

            $qtdTentativasPermitidas = $this->getAvaliacao()->getQtdTentativasPermitidas();

            if (
                $qtdTentativasPermitidas == 0
                || $this->getQtdTentativas() < $qtdTentativasPermitidas
            ) {

                $this->bloquear();

            } else {

                $this->desativar();

            }

        }

        return $this->getSituacao();
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
            'respostas' => $respostas,
            'persistido' => $this->isPersistido()
        );
    }

    /**
     * Converte os dados das propriedades do objeto em um array para ser persistido no BD Cassandra
     *
     * @return array
     */
    public function toCassandra()
    {
        return array(
            'id' => $this->getId(),
            'dataAplicacao' => $this->getDataAplicacao(),
            'tempoDecorrido' => $this->getTempoDecorrido(),
            'nota' => $this->getNota(),
            'qtdTentativas' => $this->getQtdTentativas(),
            'avaliacao' => ( $this->_avaliacao instanceof WeLearn_Cursos_Avaliacoes_Avaliacao )
                           ? $this->getAvaliacao()->getId() : '',
            'participacaoCurso' => ( $this->_avaliacao instanceof WeLearn_Cursos_ParticipacaoCurso )
                                   ? $this->getParticipacaoCurso()->getId() : '',
            'status' => $this->getStatus(),
            'situacao' => $this->getSituacao()
        );
    }

    /**
     * @return array
     */
    public function respostasToCassandra()
    {
        $respostasColumnArray = array();

        for ($i = 0; $i < count($this->_respostas); $i++) {
            $respostasColumnArray[ $this->_respostas[$i]->getQuestaoId() ] = $this->_respostas[$i]->getId();
        }

        return $respostasColumnArray;
    }

    /**
     * @return string
     */
    public function getCFKey()
    {
        return self::gerarCFKey(
            $this->getParticipacaoCurso(),
            $this->getAvaliacao()
        );
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $curso
     * @return string
     */
    public static function gerarCFKey(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso,
                                      WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacao)
    {
        return $participacaoCurso->getId() . '::' . $avaliacao->getId();
    }

    /**
     * @param $cfKey
     * @return array|bool
     */
    public static function CFKeyToArray( $cfKey )
    {
        $explodedCfKey = explode('::', $cfKey);

        if ( count( $explodedCfKey ) == 3 ) {

            return array(
                'aluno' => $explodedCfKey[0],
                'curso' => $explodedCfKey[1],
                'avaliacao' => $explodedCfKey[2]
            );

        }

        return false;
    }
}