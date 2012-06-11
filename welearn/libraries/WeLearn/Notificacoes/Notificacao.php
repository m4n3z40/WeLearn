<?php

class WeLearn_Notificacoes_Notificacao extends WeLearn_DTO_AbstractDTO implements WeLearn_Notificacoes_INotificacao
{
    /**
     * @var string
     */
    private $_dataEnvio;

    /**
     * @var string
     */
    private $_msg;

    /**
     * @var string
     */
    private $_url;

    /**
     * @var string
     */
    private $_id;

    /**
     * @var int
     */
    private $_status = WeLearn_Notificacoes_StatusNotificacao::NOVO;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_destinatario;

    /**
     * @var SplObjectStorage
     */
    private $_notificadores;

    public function __construct()
    {
        parent::__construct();

        $this->_notificadores = new SplObjectStorage();
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
     * @param string $msg
     */
    public function setMsg($msg)
    {
        $this->_msg = (string)$msg;
    }

    /**
     * @return string
     */
    public function getMsg()
    {
        return $this->_msg;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = (string)$url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
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
    public function ler()
    {
        $this->setStatus( WeLearn_Notificacoes_StatusNotificacao::LIDO );
    }

    /**
     * @param WeLearn_Notificacoes_INotificador $notificador
     * @return void
     */
    public function adicionarNotificador(WeLearn_Notificacoes_INotificador $notificador)
    {
        $this->_notificadores->attach( $notificador );
    }

    /**
     * @param WeLearn_Notificacoes_INotificador $notificador
     * @return void
     */
    public function removerNotificador(WeLearn_Notificacoes_INotificador $notificador)
    {
        $this->_notificadores->detach( $notificador );
    }

    /**
     *@return void
     */
    public function notificar()
    {
        foreach ( $this->_notificadores as $notificador ) {
            $notificador->notificar( $this );
        }
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
            'dataEnvio' => $this->getDataEnvio(),
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'msg' => $this->getMsg(),
            'url' => $this->getMsg(),
            'destinatario' => ( $this->_destinatario instanceof WeLearn_Usuarios_Usuario )
                              ? $this->getDestinatario()->toArray() : '',
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
            'dataEnvio' => $this->getDataEnvio(),
            'id' => $this->getId(),
            'status' => $this->getStatus(),
            'msg' => $this->getMsg(),
            'url' => $this->getUrl(),
            'destinatario' => ( $this->_destinatario instanceof WeLearn_Usuarios_Usuario )
                              ? $this->getDestinatario()->getId() : ''
        );
    }
}