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
     * @var string
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

    /**
     * Converte os dados das propriedades do objeto em um array para ser persistido no BD Cassandra
     *
     * @return array
     */
    public function toCassandra()
    {
        $selfArray  = $this->toArray();

        unset( $selfArray['persistido'] );

        return $selfArray;
    }
}