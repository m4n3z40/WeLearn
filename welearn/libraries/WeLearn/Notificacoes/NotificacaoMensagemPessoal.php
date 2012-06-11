<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoMensagemPessoal extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Usuarios_MensagemPessoal
     */
    private $_mensagemPessoal;

    /**
     * @param \WeLearn_Usuarios_MensagemPessoal $mensagemPessoal
     */
    public function setMensagemPessoal(WeLearn_Usuarios_MensagemPessoal $mensagemPessoal)
    {
        $this->_mensagemPessoal = $mensagemPessoal;
    }

    /**
     * @return \WeLearn_Usuarios_MensagemPessoal
     */
    public function getMensagemPessoal()
    {
        return $this->_mensagemPessoal;
    }

    public function getMsg()
    {
        if ( null === $this->_msg ) {

            $linkMsg = anchor(
                '/usuario/mensagem/listar/' . $this->getMensagemPessoal()->getRemetente()->getId(),
                'mensagem pessoal'
            );

            $linkRemetente = anchor(
                '/perfil/' . $this->getMensagemPessoal()->getRemetente()->getId(),
                $this->getMensagemPessoal()->getRemetente()->getNome()
            );

            $this->setMsg( $linkRemetente . ' enviou uma ' . $linkMsg . ' para você.' );

        }

        return parent::getMsg();
    }

    public function getUrl()
    {
        if ( null === $this->_url ) {

            $this->setUrl( site_url('/usuario/mensagem/listar/' . $this->getMensagemPessoal()->getRemetente()->getId()) );

        }

        return parent::getUrl();
    }
}
