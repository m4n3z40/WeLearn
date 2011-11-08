<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 14:27
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Enquetes_AlternativaEnquete extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_txtAlternativa;

    /**
     * @var string
     */
    private $_enqueteId;

    /**
     * @var int
     */
    private $_totalVotos;

    /**
     * @param int $id
     * @param string $txtAlternativa
     * @param string_ $enqueteId
     */
    public function __construct($id = 0, $txtAlternativa = '', $enqueteId = null)
    {
        $dados = array(
            'id' => $id,
            'txtAlternativa' => $txtAlternativa,
            'enqueteId' => $enqueteId
        );

        parent::__construct($dados);
    }

    /**
     * @param string $enqueteId
     */
    public function setEnqueteId($enqueteId)
    {
        $this->_enqueteId = $enqueteId;
    }

    /**
     * @return string
     */
    public function getEnqueteId()
    {
        return $this->_enqueteId;
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
     * @return int
     */
    public function getTotalVotos()
    {
        return $this->_totalVotos;
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
     * @return void
     */
    public function recuperarQtdTotalVotos()
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
        return array(
            'id' => $this->getId(),
            'txtAlternativa' => $this->getTxtAlternativa(),
            'enqueteId' => $this->getEnqueteId(),
            'totalVotos' => $this->getTotalVotos(),
            'persistido' => $this->isPersistido()
        );
    }
}
