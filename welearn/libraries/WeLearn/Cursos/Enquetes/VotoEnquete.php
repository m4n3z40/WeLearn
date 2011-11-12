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
     * @var int
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
    public function __construct($dataVoto = 0,
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
     * @param int $dataVoto
     */
    public function setDataVoto($dataVoto)
    {
        $this->_dataVoto = (int)$dataVoto;
    }

    /**
     * @return int
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

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'dataVoto' => $this->getDataVoto(),
            'enquete' => $this->getEnquete()->toArray(),
            'votante' => $this->getVotante()->toArray(),
            'alternativa' => $this->getAlternativa()->toArray(),
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
            'dataVoto' => $this->getDataVoto(),
            'enquete' => ($this->_enquete instanceof WeLearn_Cursos_Enquetes_Enquete) ? $this->getEnquete()->getId() : '',
            'votante' => ($this->_votante instanceof WeLearn_Usuarios_Usuario) ? $this->getVotante()->getId() : '',
            'alternativa' => ($this->_alternativa instanceof WeLearn_Cursos_Enquetes_AlternativaEnquete) ? $this->getAlternativa()->getId() : ''
        );
    }
}