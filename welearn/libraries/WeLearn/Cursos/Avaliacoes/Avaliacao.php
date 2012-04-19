<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 23:30
 *
 * Description:
 *
 */

class WeLearn_Cursos_Avaliacoes_Avaliacao extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_nome;

    /**
     * @var int
     */
    private $_qtdQuestoes;

    /**
     * @var int
     */
    private $_qtdQuestoesExibir;

    /**
     * @var float
     */
    private $_notaMinima;

    /**
     * @var float
     */
    private $_tempoDuracaoMax;

    /**
     * @var int
     */
    private $_qtdTentativasPermitidas;

    /**
     * @var WeLearn_Cursos_Conteudo_Modulo
     */
    private $_modulo;

    /**
     * @var array
     */
    private $_questoes;

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
     * @param \WeLearn_Cursos_Conteudo_Modulo $modulo
     */
    public function setModulo(WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $this->_modulo = $modulo;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Modulo
     */
    public function getModulo()
    {
        return $this->_modulo;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->_nome = (string)$nome;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->_nome;
    }

    /**
     * @param float $notaMinima
     */
    public function setNotaMinima($notaMinima)
    {
        $this->_notaMinima = (float)$notaMinima;
    }

    /**
     * @return float
     */
    public function getNotaMinima()
    {
        return $this->_notaMinima;
    }

    /**
     * @param int $qtdQuestoes
     */
    public function setQtdQuestoes($qtdQuestoes)
    {
        $this->_qtdQuestoes = (int)$qtdQuestoes;
    }

    /**
     * @return int
     */
    public function getQtdQuestoes()
    {
        return $this->_qtdQuestoes;
    }

    /**
     * @param int $qtdQuestoesExibir
     */
    public function setQtdQuestoesExibir($qtdQuestoesExibir)
    {
        $this->_qtdQuestoesExibir = (int)$qtdQuestoesExibir;
    }

    /**
     * @return int
     */
    public function getQtdQuestoesExibir()
    {
        return $this->_qtdQuestoesExibir;
    }

    /**
     * @param int $qtdTentativasPermitidas
     */
    public function setQtdTentativasPermitidas($qtdTentativasPermitidas)
    {
        $this->_qtdTentativasPermitidas = (int)$qtdTentativasPermitidas;
    }

    /**
     * @return int
     */
    public function getQtdTentativasPermitidas()
    {
        return $this->_qtdTentativasPermitidas;
    }

    /**
     * @param array $questoes
     */
    public function setQuestoes(array $questoes)
    {
        $this->_questoes = $questoes;
    }

    /**
     * @return array
     */
    public function getQuestoes()
    {
        return $this->_questoes;
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
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        $questoes = array();
        if (!is_null($this->_questoes)) {
            foreach ($this->getQuestoes() as $questao) {
                $questoes[] = $questao->toArray();
            }
        }

        return array(
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'qtdQuestoes' => $this->getQtdQuestoes(),
            'qtdQuestoesExibir' => $this->getQtdQuestoesExibir(),
            'notaMinima' => $this->getNotaMinima(),
            'tempoDuracaoMax' => $this->getTempoDuracaoMax(),
            'qtdTentativasPermitidas' => $this->getQtdTentativasPermitidas(),
            'modulo' => $this->getModulo()->toArray(),
            'questoes' => $questoes,
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
            'nome' => $this->getNome(),
            'qtdQuestoes' => $this->getQtdQuestoes(),
            'qtdQuestoesExibir' => $this->getQtdQuestoesExibir(),
            'notaMinima' => $this->getNotaMinima(),
            'tempoDuracaoMax' => $this->getTempoDuracaoMax(),
            'qtdTentativasPermitidas' => $this->getQtdTentativasPermitidas(),
            'modulo' => ($this->_modulo instanceof WeLearn_Cursos_Conteudo_Modulo)
                        ? $this->getModulo()->getId() : ''
        );
    }
}
