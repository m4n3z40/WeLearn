<?php
/**
 * Created by Allan Marques
 * Date: 27/07/11
 * Time: 02:50
 * 
 * Description:
 *
 */
 
abstract class WeLearn_DAO_AbstractDAO implements WeLearn_DAO_IDAO
{
    /**
     * @var ColumnFamily
     */
    protected $_cf;

    /**
     * @var string
     */
    protected $_nomeCF;

    /**
     * @var array
     */
    protected $_infoColunas;


    public function __construct()
    {

    }

    /**
     * @abstract
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    abstract protected function _adicionar(WeLearn_DTO_IDTO &$dto);

    /**
     * @abstract
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    abstract protected function _atualizar(WeLearn_DTO_IDTO $dto);

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return boolean
     */
    public function salvar(WeLearn_DTO_IDTO &$dto)
    {
        if ($dto->isPersistido()) {
            return $this->_atualizar($dto);
        } else {
            return $this->_adicionar($dto);
        }
    }

    /**
     * @param \ColumnFamily $cf
     */
    public function setCf($cf)
    {
        $this->_cf = $cf;
    }

    /**
     * @return \ColumnFamily
     */
    public function getCf()
    {
        return $this->_cf;
    }

    /**
     * @return array
     */
    public function getInfoColunas()
    {
        return $this->_infoColunas;
    }

    /**
     * @return string
     */
    public function getNomeCF()
    {
        return $this->_nomeCF;
    }
}
