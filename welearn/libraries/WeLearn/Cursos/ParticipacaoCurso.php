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
    private $_frequenciaTotal = 0;

    /**
     * @var string
     */
    private $_dataUltimoAcesso;

    /**
     * @var float
     */
    private $_crFinal = 0;

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
    private $_situacao = WeLearn_Cursos_SituacaoParticipacaoCurso::INSCRICAO_EM_ESPERA;

    /**
     * @var string
     */
    private $_tipoConteudoAtual = WeLearn_Cursos_Conteudo_TipoConteudo::NENHUM;

    /**
     * @var WeLearn_Cursos_Conteudo_Pagina
     */
    private $_paginaAtual;

    /**
     * @var WeLearn_Cursos_Avaliacoes_Avaliacao
     */
    private $_avaliacaoAtual;

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
     * @param \float $crFinal
     */
    public function setCrFinal($crFinal)
    {
        $this->_crFinal = (float)$crFinal;
    }

    /**
     * @return \float
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
     * @param \WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacaoAtual
     */
    public function setAvaliacaoAtual(WeLearn_Cursos_Avaliacoes_Avaliacao $avaliacaoAtual)
    {
        $this->_avaliacaoAtual = $avaliacaoAtual;
    }

    /**
     * @return \WeLearn_Cursos_Avaliacoes_Avaliacao
     */
    public function getAvaliacaoAtual()
    {
        return $this->_avaliacaoAtual;
    }

    /**
     * @param string $tipoConteudoAtual
     */
    public function setTipoConteudoAtual($tipoConteudoAtual)
    {
        $this->_tipoConteudoAtual = (string)$tipoConteudoAtual;
    }

    /**
     * @return string
     */
    public function getTipoConteudoAtual()
    {
        return $this->_tipoConteudoAtual;
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
    public function concluirCurso()
    {
        $this->setSituacao( WeLearn_Cursos_SituacaoParticipacaoCurso::CURSO_CONCLUIDO );
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
            'certificado' => is_null($this->_certificado) ? '' : $this->getCertificado()->toArray(),
            'situacao' => $this->getSituacao(),
            'tipoConteudoAtual' => $this->getTipoConteudoAtual(),
            'paginaAtual' => is_null($this->_paginaAtual) ? '' : $this->getPaginaAtual()->toArray(),
            'avaliacaoAtual' => is_null($this->_avaliacaoAtual) ? '' : $this->getAvaliacaoAtual()->toArray(),
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
            'dataInscricao' => $this->getDataInscricao(),
            'frequenciaTotal' => $this->getFrequenciaTotal(),
            'dataUltimoAcesso' => $this->getDataUltimoAcesso(),
            'crFinal' => $this->getCrFinal(),
            'curso' => ( $this->_curso instanceof WeLearn_Cursos_Curso )
                       ? $this->getCurso()->getId() : '',
            'aluno' => ( $this->_aluno instanceof WeLearn_Usuarios_Aluno )
                       ? $this->getAluno()->getId() : '',
            'certificado' => ( $this->_certificado instanceof WeLearn_Cursos_Certificado )
                             ? $this->getCertificado()->getId() : '',
            'situacao' => $this->getSituacao(),
            'tipoConteudoAtual' => $this->getTipoConteudoAtual(),
            'paginaAtual' => ( $this->_paginaAtual instanceof WeLearn_Cursos_Conteudo_Pagina )
                             ? $this->getPaginaAtual()->getId() : '',
            'avaliacaoAtual' => ( $this->_avaliacaoAtual instanceof WeLearn_Cursos_Avaliacoes_Avaliacao )
                                ? $this->getAvaliacaoAtual()->getId() : ''
        );
    }

    /**
     * @return string
     */
    public function getCFKey()
    {
        return self::gerarCFKey(
            $this->getAluno(),
            $this->getCurso()
        );
    }

    /**
     * @param WeLearn_Usuarios_Aluno $aluno
     * @param WeLearn_Cursos_Curso $curso
     * @return string
     */
    public static function gerarCFKey(WeLearn_Usuarios_Aluno $aluno, WeLearn_Cursos_Curso $curso)
    {
        return $aluno->getId() . '::' . $curso->getId();
    }

    /**
     * @param $cfKey
     * @return array|bool
     */
    public static function CFKeyToArray( $cfKey )
    {
        $explodedCfKey = explode('::', $cfKey);

        if ( count( $explodedCfKey ) == 2 ) {

            return array(
                'aluno' => $explodedCfKey[0],
                'curso' => $explodedCfKey[1]
            );

        }

        return false;
    }
}
