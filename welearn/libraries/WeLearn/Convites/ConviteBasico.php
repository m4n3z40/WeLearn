<?php
/**
 * Created by Thiago Monteiro
 * Date: 24/07/11
 * Time: 10:50
 *
 * Description:
 *
 */

class WeLearn_Convites_ConviteBasico extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_msgConvite;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    protected $_remetente;

    /**
     * @var int
     */
    protected $_status;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $msg
     */
    public function setMsgConvite($msg)
    {
        $this->_msgConvite = (string)$msg;
    }

    /**
     * @return string
     */
    public function getMsgConvite()
    {
        return $this->_msgConvite;
    }

    /**
     * @param WeLearn_Usuarios_Usuario $remetente
     */
    public function setRemetente(WeLearn_Usuarios_Usuario $remetente)
    {
        $this->_remetente = $remetente;
    }

    /**
     * @return WeLearn_Usuarios_Usuario
     */
    public function getRemetente()
    {
        return $this->_remetente;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_status = (int)$status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     *@return void
     */
    public function aceitar()
    {
        // @todo implementar este metodo!
    }

    /**
     *@return void
     */
    public function enviar()
    {
        // @todo implementar este metodo!
    }

    /**
     *@return void
     */
    public function ler()
    {
        //@todo : implementar este metodo!
    }

    /**
     *@return void
     */
    public function recusar()
    {
        //@todo : implementar este metodo!
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
            'msgConvite' => $this->getMsgConvite(),
            'remetente' => $this->getRemetente(),
            'status' => $this->getStatus(),
            'persistido' => $this->isPersistido()
        );
    }
}