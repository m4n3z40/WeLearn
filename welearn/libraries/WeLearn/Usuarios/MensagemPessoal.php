<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 16:06
 *
 * Description:
 *
 */

class WeLearn_Usuarios_MensagemPessoal extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_mensagem;

    /**
     * @var string
     */
    private $_dataEnvio;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_remetente;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_destinatario;

    /**
     * @var int
     */
    private $_status;

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
     * @param \WeLearn_Usuarios_Usuario $remetente
     */
    public function setRemetente(WeLearn_Usuarios_Usuario $remetente)
    {
        $this->_remetente = $remetente;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getRemetente()
    {
        return $this->_remetente;
    }

    /**
     * @param string $mensagem
     */
    public function setMensagem($mensagem)
    {
        $this->_mensagem = (string)$mensagem;
    }

    /**
     * @return string
     */
    public function getMensagem()
    {
        return $this->_mensagem;
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
     * @param \WeLearn_Usuarios_Usuario $destinatario
     */
    public function setDestinatario(WeLearn_Usuarios_Usuario $destinatario)
    {
        $this->_destinatario = $destinatario;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getDestinatario()
    {
        return $this->_destinatario;
    }

    /**
     * @param string $dataEnvio
     */
    public function setDataEnvio($dataEnvio)
    {
        $this->_dataEnvio = (string)$dataEnvio;
    }

    /**
     * @return string
     */
    public function getDataEnvio()
    {
        return $this->_dataEnvio;
    }

    /**
     * @return void
     */
    public function enviar()
    {
        //TODO: Implementar este método!
    }

    /**
     * @return void
     */
    public function ler()
    {
        //TODO: Implementar este método!
    }
}