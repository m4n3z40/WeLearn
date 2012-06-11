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
     * @var string
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
     * @var string
     */
    private $_avaliacaoId;

    /**
     * @var WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao
     */
    private $_alternativaCorreta;

    /**
     * @var array(WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao)
     */
    private $_alternativasIncorretas;

    /**
     * @param string $avaliacaoId
     */
    public function setAvaliacaoId($avaliacaoId)
    {
        $this->_avaliacaoId = (string)$avaliacaoId;
    }

    /**
     * @return string
     */
    public function getAvaliacaoId()
    {
        return $this->_avaliacaoId;
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
     * @param \WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativaCorreta
     */
    public function setAlternativaCorreta(WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao $alternativaCorreta)
    {
        $this->_alternativaCorreta = $alternativaCorreta;
    }

    /**
     * @return \WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao
     */
    public function getAlternativaCorreta()
    {
        return $this->_alternativaCorreta;
    }

    /**
     * @param \array(WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao) $alternativasIncorretas
     */
    public function setAlternativasIncorretas(array $alternativasIncorretas)
    {
        $this->_alternativasIncorretas = $alternativasIncorretas;
    }

    /**
     * @return \array(WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao)
     */
    public function getAlternativasIncorretas()
    {
        return $this->_alternativasIncorretas;
    }

    /**
     * @return array
     */
    public function getAlternativasRandomizadas()
    {
        if ( !($this->_alternativaCorreta instanceof WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao)
           || empty($this->_alternativasIncorretas)) {

            return array();
        }

        $alternativasIncorretas = $this->getAlternativasIncorretas();
        shuffle($alternativasIncorretas);

        $alternativasRandomizadas = array_slice(
            $alternativasIncorretas,
            0,
            $this->getQtdAlternativasExibir() - 1
        );

        array_push($alternativasRandomizadas, $this->getAlternativaCorreta());
        shuffle($alternativasRandomizadas);

        return $alternativasRandomizadas;
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        $alternativasIncorretas = array();
        if  (!is_null($this->_alternativasIncorretas)) {
            foreach ($this->getAlternativasIncorretas() as $alternativaIncorreta) {
                $alternativasIncorretas[] = $alternativaIncorreta->toArray();
            }
        }

        return array(
            'id' => $this->getId(),
            'enunciado' => $this->getEnunciado(),
            'qtdAlternativas' => $this->getQtdAlternativas(),
            'qtdAlternativasExibir' => $this->getQtdAlternativasExibir(),
            'avaliacaoId' => $this->getAvaliacaoId(),
            'alternativaCorreta' => ($this->_alternativaCorreta instanceof
                                     WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao)
                                     ? $this->getAlternativaCorreta()->toArray() : '',
            'alternativasIncorretas' => $alternativasIncorretas,
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
            'enunciado' => $this->getEnunciado(),
            'qtdAlternativas' => $this->getQtdAlternativas(),
            'qtdAlternativasExibir' => $this->getQtdAlternativasExibir(),
            'alternativaCorreta' => ($this->_alternativaCorreta instanceof
                                     WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao)
                                     ? $this->getAlternativaCorreta()->getId() : '',
            'alternativasIncorretas' => $this->_compilarIdsAlternativasIncorretas(),
            'avaliacaoId' => $this->getAvaliacaoId()
        );
    }

    private function _compilarIdsAlternativasIncorretas()
    {
        if ( empty($this->_alternativasIncorretas) ) {
            return '';
        }

        $arrayIds = array();
        foreach ($this->getAlternativasIncorretas() as $alternativaIncorreta) {
            if ($alternativaIncorreta instanceof WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao) {
                $arrayIds[] = $alternativaIncorreta->getId();
            }
        }

        return implode('|', $arrayIds);
    }
}