<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 23:19
 *
 * Description:
 *
 */

class WeLearn_Cursos_Avaliacoes_AlternativaAvaliacao extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @var boolean
     */
    private $_correta;

    /**
     * @var string
     */
    private $_txtAlternativa;

    /**
     * @var string
     */
    private $_questaoId;

    /**
     * @param int $id
     * @param bool $correta
     * @param string $txtAlternativa
     * @param null|WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao
     */
    public function __construct($id = 0, $correta = false, $txtAlternativa = '', $questaoId = '')
    {
        $dados = array(
            'id' => $id,
            'correta' => $correta,
            'txtAlternativa' => $txtAlternativa,
            'questaoId' => $questaoId
        );

        parent::__construct($dados);
    }

    /**
     * @param boolean $correta
     */
    public function setCorreta($correta)
    {
        $this->_correta = (boolean)$correta;
    }

    /**
     * @return boolean
     */
    public function isCorreta()
    {
        return $this->_correta;
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
     * @param string $txtAlternativa
     */
    public function setTxtAlternativa($txtAlternativa)
    {
        $this->_txtAlternativa = (string)$txtAlternativa;
    }

    /**
     * @return string
     */
    public function getTxtAlternativa()
    {
        return $this->_txtAlternativa;
    }

    /**
     * @param string $questaoId
     */
    public function setQuestaoId($questaoId)
    {
        $this->_questaoId = (string)$questaoId;
    }

    /**
     * @return string
     */
    public function getQuestaoId()
    {
        return $this->_questaoId;
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
            'id' => $this->getId(),
            'correta' => $this->isCorreta(),
            'txtAlternativa' => $this->getTxtAlternativa(),
            'questaoId' => $this->getQuestaoId(),
            'persistido' => $this->isPersistido()
        );
    }
}