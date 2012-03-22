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
     * @var string
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
    private $_totalVotos = 0;

    /**
     * @var float
     */
    private $_proporcaoParcial = 0;

    /**
     * @param int $id
     * @param string $txtAlternativa
     * @param string_ $enqueteId
     */
    public function __construct($id = '', $txtAlternativa = '', $enqueteId = '')
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
        $this->_enqueteId = (string)$enqueteId;
    }

    /**
     * @return string
     */
    public function getEnqueteId()
    {
        return $this->_enqueteId;
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
     * @param int $votos
     * @return void
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
     * @param float $proporcaoParcial
     */
    public function setProporcaoParcial($proporcaoParcial)
    {
        $this->_proporcaoParcial = round((float)$proporcaoParcial, 2);
    }

    /**
     * @return float
     */
    public function getProporcaoParcial()
    {
        return $this->_proporcaoParcial;
    }

    /**
     * @return void
     */
    public function recuperarQtdTotalVotos()
    {
        WeLearn_DAO_DAOFactory::create('EnqueteDAO')->recuperarQtdTotalVotosPorAlternativa($this);
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
            'proporcaoParcial' => $this->getProporcaoParcial(),
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
            'txtAlternativa' => $this->getTxtAlternativa(),
            'enqueteId' => $this->getEnqueteId(),
            'totalVotos' => $this->getTotalVotos()
        );
    }
}
