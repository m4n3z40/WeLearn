<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 14:31
 *
 * Description:
 *
 */

/**
 *
 */
class WeLearn_Usuarios_ListaDeIM extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_idIM;

    /**
     * @var WeLearn_Usuarios_InstantMessenger
     */
    private $_messenger;

    /**
     * @var string
     */
    private $_usuarioId;

    /**
     * @param string $idIM
     * @param null|WeLearn_Usuarios_InstantMessenger $messenger
     * @param string $usuarioId
     */
    public function __construct($idIM = '',
        WeLearn_Usuarios_InstantMessenger $messenger = null,
        $usuarioId = '')
    {
        $dados = array(
            'idIM' => $idIM,
            'messenger' => $messenger,
            'usuarioId' => $usuarioId
        );

        parent::__construct($dados);
    }

    /**
     * @param string $idIM
     */
    public function setIdIM($idIM)
    {
        $this->_idIM = (string)$idIM;
    }

    /**
     * @return string
     */
    public function getIdIM()
    {
        return $this->_idIM;
    }

    /**
     * @param \WeLearn_Usuarios_InstantMessenger $messenger
     */
    public function setMessenger(WeLearn_Usuarios_InstantMessenger $messenger)
    {
        $this->_messenger = $messenger;
    }

    /**
     * @return \WeLearn_Usuarios_InstantMessenger
     */
    public function getMessenger()
    {
        return $this->_messenger;
    }

    /**
     * @param string $usuarioId
     */
    public function setUsuarioId($usuarioId)
    {
        $this->_usuarioId = (string)$usuarioId;
    }

    /**
     * @return string
     */
    public function getUsuarioId()
    {
        return $this->_usuarioId;
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
            'idIM' => $this->getIdIM(),
            'messenger' => $this->getMessenger()->toArray(),
            'usuarioId' => $this->getUsuarioId(),
            'persistido' => $this->isPersistido()
        );
    }


}
