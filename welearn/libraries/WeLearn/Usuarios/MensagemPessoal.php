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
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_mensagem;

    /**
     * @var int
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
    private $_status = WeLearn_Usuarios_StatusMP::NOVO;

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
     * @param int $dataEnvio
     */
    public function setDataEnvio($dataEnvio)
    {
        $this->_dataEnvio = (int)$dataEnvio;
    }

    /**
     * @return int
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
            'mensagem' => $this->getMensagem(),
            'dataEnvio' => $this->getDataEnvio(),
            'remetente' => $this->getRemetente()->toArray(),
            'destinatario' => $this->getDestinatario()->toArray(),
            'status' => $this->getStatus(),
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
            'mensagem' => $this->getMensagem(),
            'dataEnvio' => $this->getDataEnvio(),
            'remetente' => ($this->_remetente instanceof WeLearn_Usuarios_Usuario) ? $this->getRemetente() : '',
            'destinatario' => ($this->_destinatario instanceof WeLearn_Usuarios_Usuario) ? $this->getDestinatario() : '',
            'status' => $this->getStatus()
        );
    }
}