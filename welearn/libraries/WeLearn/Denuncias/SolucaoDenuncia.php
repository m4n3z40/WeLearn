<?php
/**
 * Created by Thiago Monteiro
 * Date: 23/07/11
 * Time: 13:00
 *
 * Description:
 *
 */
class WeLearn_Denuncias_SolucaoDenuncia extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @var string
     */
    private $_dataSolucao;

    /**
     * @var WeLearn_Denuncias_Denuncia
     */
    private $_denuncia;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @param \WeLearn_Usuarios_Usuario $criador
     */
    public function setCriador(WeLearn_Usuarios_Usuario $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getCriador()
    {
        return $this->_criador;
    }

    /**
     * @param \WeLearn_Denuncias_Denuncia $denuncia
     */
    public function setDenuncia(WeLearn_Denuncias_Denuncia $denuncia)
    {
        $this->_denuncia = $denuncia;
    }

    /**
     * @return \WeLearn_Denuncias_Denuncia
     */
    public function getDenuncia()
    {
        return $this->_denuncia;
    }

    /**
     * @param string $dataSolucao
     */
    public function setDataSolucao($dataSolucao)
    {
        $this->_dataSolucao = (string)$dataSolucao;
    }

    /**
     * @return string
     */
    public function getDataSolucao()
    {
        return $this->_dataSolucao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->_descricao = (string)$descricao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->_descricao;
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
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'criador' => $this->getCriador()->toArray(),
            'dataSolucao' => $this->getDataSolucao(),
            'denuncia' => $this->getDenuncia()->toArray(),
            'descricao' => $this->getDescricao(),
            'persistido' => $this->isPersistido()
        );
    }
}