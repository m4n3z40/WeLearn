<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 14:27
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Enquetes_VotoEnquete extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_dataVoto;

    /**
     * @var WeLearn_Cursos_Enquetes_Enquete
     */
    private $_enquete;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_votante;

    /**
     * @var WeLearn_Cursos_Enquetes_AlternativaEnquete
     */
    private $_alternativa;

    /**
     * @param string $dataVoto
     * @param null|WeLearn_Cursos_Enquetes_Enquete $enquete
     * @param null|WeLearn_Usuarios_Usuario $votante
     * @param null|WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa
     */
    public function __construct($dataVoto = '',
                                WeLearn_Cursos_Enquetes_Enquete $enquete = null,
                                WeLearn_Usuarios_Usuario $votante = null,
                                WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa = null)
    {
        $dados = array(
            'dataVoto' => $dataVoto,
            'enquete' => $enquete,
            'votante' => $votante,
            'alternativa' => $alternativa
        );

        parent::__construct($dados);
    }

    /**
     * @param \WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa
     */
    public function setAlternativa(WeLearn_Cursos_Enquetes_AlternativaEnquete $alternativa)
    {
        $this->_alternativa = $alternativa;
    }

    /**
     * @return \WeLearn_Cursos_Enquetes_AlternativaEnquete
     */
    public function getAlternativa()
    {
        return $this->_alternativa;
    }

    /**
     * @param string $dataVoto
     */
    public function setDataVoto($dataVoto)
    {
        $this->_dataVoto = (string)$dataVoto;
    }

    /**
     * @return string
     */
    public function getDataVoto()
    {
        return $this->_dataVoto;
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
     * @param \WeLearn_Usuarios_Usuario $votante
     */
    public function setVotante(WeLearn_Usuarios_Usuario $votante)
    {
        $this->_votante = $votante;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getVotante()
    {
        return $this->_votante;
    }
}