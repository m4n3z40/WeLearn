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
     * @abstract
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    abstract protected function _adicionar(WeLearn_DTO_IDTO &$dto);

    /**
     * @abstract
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    abstract protected function _atualizar(WeLearn_DTO_IDTO $dto);

    /**
     * @param WeLearn_DTO_IDTO $dto
     * @return void
     */
    public function salvar(WeLearn_DTO_IDTO &$dto)
    {
        if ($dto->isPersistido()) {
            $this->_atualizar($dto);
        } else {
            $this->_adicionar($dto);
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
     * @return string
     */
    public function getNomeCF()
    {
        return $this->_nomeCF;
    }
}