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
     * @var WeLearn_Cursos_Enquetes_Enquete
     */
    private $_enquete;

    /**
     * @var int
     */
    private $_totalVotos;

    /**
     * @param int $id
     * @param string $txtAlternativa
     * @param null|WeLearn_Cursos_Enquetes_ $enquete
     */
    public function __construct($id = 0, $txtAlternativa = '', WeLearn_Cursos_Enquetes_ $enquete = null)
    {
        $dados = array(
            'id' => $id,
            'txtAlternativa' => $txtAlternativa,
            'enquete' => $enquete
        );

        parent::__construct($dados);
    }

    /**
     * @param \WeLearn_Cursos_Enquetes_Enquete $enquete
     */
    public function setEnquete(WeLearn_Cursos_Enquetes_Enquete $enquete)
    {
        $this->_enquete = $enquete;
    }

    /**
     * @return \WeLearn_Cursos_Enquetes_Enquete
     */
    public function getEnquete()
    {
        return $this->_enquete;
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
}
