<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 19/06/12
 * Time: 11:04
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificadorTempoReal implements WeLearn_Notificacoes_INotificador
{
    /**
     * @var string
     */
    private $_urlServidor = 'http://localhost';

    /**
     * @var int
     */
    private $_portaServidor = 8080;

    /**
     * @var string
     */
    private $_chaveSeguranca = '22cda956cf8a821c1d11ad5b8d3a6f6f708a755f';

    /**
     * @var array
     */
    private $_notificacoes;

    private $_dadosPost;

    public function __construct()
    {
        $this->_dadosPost = array(
            'chaveSeguranca' => $this->_chaveSeguranca,
            'dadosJson' => ''
        );

        $this->_notificacoes = array();
    }

    /**
     * @param WeLearn_Notificacoes_Notificacao $notificacao
     */
    public function notificar(WeLearn_Notificacoes_Notificacao $notificacao)
    {
        $this->_notificacoes[] = array(
            'sid' => $notificacao->getDestinatario()->getId(),
            'usuario' => $notificacao->getDestinatario()->getNomeUsuario(),
            'tipo' => get_class( $notificacao ),
            'msg' => $notificacao->getMsg(),
            'url' => $notificacao->getUrl()
        );
    }

    /**
     * Envia todas as notificações registradas ao destruir este objeto.
     */
    public function __destruct()
    {
        $this->_dadosPost['dadosJson'] = Zend_Json::encode( $this->_notificacoes );

        $url = rtrim($this->_urlServidor, '/') . ':' . $this->_portaServidor;

        $curl = curl_init( $url );

        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($this->_dadosPost) );

        $JsonResponse = curl_exec( $curl );

        curl_close( $curl );
    }
}
