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
     * @var WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao
     */
    private $_questao;

    /**
     * @param int $id
     * @param bool $correta
     * @param string $txtAlternativa
     * @param null|WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao
     */
    public function __construct($id = 0,
        $correta = false,
        $txtAlternativa = '',
        WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao = null)
    {
        $dados = array(
            'id' => $id,
            'correta' => $correta,
            'txtAlternativa' => $txtAlternativa,
            'questao' => $questao
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
     * @param \WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao
     */
    public function setQuestao(WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao $questao)
    {
        $this->_questao = $questao;
    }

    /**
     * @return \WeLearn_Cursos_Avaliacoes_QuestaoAvaliacao
     */
    public function getQuestao()
    {
        return $this->_questao;
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
}