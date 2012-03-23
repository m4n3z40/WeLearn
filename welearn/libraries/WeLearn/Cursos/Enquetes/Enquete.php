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
     * @var string
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
     * @var int
     */
    private $_dataCriacao;

    /**
     * @var int
     */
    private $_dataExpiracao;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @var int
     */
    private $_status = WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA;

    /**
     * @var int
     */
    private $_situacao = WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA;

    /**
     * @var array
     */
    private $_alternativas = array();

    /**
     * @var int
     */
    private $_totalVotos = 0;

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
    public function setCriador(WeLearn_Usuarios_Usuario $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
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
     * @param int $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->_dataCriacao = (int)$dataCriacao;
    }

    /**
     * @return int
     */
    public function getDataCriacao()
    {
        return $this->_dataCriacao;
    }

    /**
     * @param int $dataExpiracao
     */
    public function setDataExpiracao($dataExpiracao)
    {
        $this->_dataExpiracao = (int)$dataExpiracao;
    }

    /**
     * @return int
     */
    public function getDataExpiracao()
    {
        return $this->_dataExpiracao;
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
     * @param int $votos
     * @return int
     */
    public function setTotalVotos($votos)
    {
        $this->_totalVotos = (int)$votos;
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
        WeLearn_DAO_DAOFactory::create('EnqueteDAO')->recuperarAlternativas($this);
    }

    public function adicionarAlternativa(WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa)
    {
        array_push($this->_alternativas, $alternativa);
    }

    public function alterarSituacao()
    {
        if ( $this->_situacao === WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA ) {
            $this->setSituacao(WeLearn_Cursos_Enquetes_SituacaoEnquete::FECHADA);
        } else {
            $this->setSituacao(WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA);
        }
    }

    public function alterarStatus()
    {
        if ( $this->_status === WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA ) {
            $this->setStatus(WeLearn_Cursos_Enquetes_StatusEnquete::INATIVA);
        } else {
            $this->setStatus(WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA);
        }
    }

    public function recuperarQtdTotalVotos()
    {
        WeLearn_DAO_DAOFactory::create('EnqueteDAO')->recuperarQtdTotalVotos($this);
    }

    public function zerarVotos()
    {
        WeLearn_DAO_DAOFactory::create('EnqueteDAO')->zerarVotos($this);
    }

    public function zerarAlternativas()
    {
        $this->_alternativas = array();
    }

    public function recuperarQtdParcialVotos()
    {
        $enqueteDao = WeLearn_DAO_DAOFactory::create('EnqueteDAO');

        $enqueteDao->recuperarQtdParcialVotos( $this );
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        $alternativas = array();
        if (!is_null($this->_alternativas)) {
            foreach ($this->getAlternativas() as $alternativa) {
                $alternativas[] = $alternativa->toArray();
            }
        }

        return array(
            'id' => $this->getId(),
            'questao' => $this->getQuestao(),
            'qtdAlternativas' => $this->getQtdAlternativas(),
            'dataCriacao' => $this->getDataCriacao(),
            'dataExpiracao' => $this->getDataExpiracao(),
            'curso' => $this->getCurso()->toArray(),
            'criador' => $this->getCriador()->toArray(),
            'status' => $this->getStatus(),
            'situacao' => $this->getSituacao(),
            'alternativas' => $alternativas,
            'totalVotos' => $this->getTotalVotos(),
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
            'questao' => $this->getQuestao(),
            'qtdAlternativas' => $this->getQtdAlternativas(),
            'dataCriacao' => $this->getDataCriacao(),
            'dataExpiracao' => $this->getDataExpiracao(),
            'curso' => ($this->_curso instanceof WeLearn_Cursos_Curso) ? $this->getCurso()->getId() : '',
            'criador' => ($this->_criador instanceof WeLearn_Usuarios_Usuario) ? $this->getCriador()->getId() : '',
            'status' => $this->getStatus(),
            'situacao' => $this->getSituacao(),
            'totalVotos' => $this->getTotalVotos()
        );
    }
}