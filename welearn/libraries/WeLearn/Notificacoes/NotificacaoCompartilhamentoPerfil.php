<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoCompartilhamentoPerfil extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Compartilhamento_Feed
     */
    private $_compartilhamento;

    /**
     * @param \WeLearn_Compartilhamento_Feed $compartilhamento
     */
    public function setCompartilhamento(WeLearn_Compartilhamento_Feed $compartilhamento)
    {
        $this->_compartilhamento = $compartilhamento;
    }

    /**
     * @return \WeLearn_Compartilhamento_Feed
     */
    public function getCompartilhamento()
    {
        return $this->_compartilhamento;
    }

    public function getMsg()
    {
        if ( null === $this->_msg ) {

            switch ( $this->getCompartilhamento()->getTipo() ) {
                case WeLearn_Compartilhamento_TipoFeed::IMAGEM:
                    $tipoStr = 'uma imagem';
                    break;
                case WeLearn_Compartilhamento_TipoFeed::VIDEO:
                    $tipoStr = 'um vídeo';
                    break;
                case WeLearn_Compartilhamento_TipoFeed::LINK:
                    $tipoStr = 'um link';
                    break;
                case WeLearn_Compartilhamento_TipoFeed::STATUS:
                default:
                    $tipoStr = 'um status';
            }

            $linkRemetente = anchor(
                '/perfil/' . $this->getCompartilhamento()->getCriador()->getId(),
                $this->getCompartilhamento()->getCriador()->getNome()
            );

            $linkDestinatario = anchor(
                '/perfil/' . $this->getDestinatario()->getId(),
                'perfil'
            );

            $this->setMsg($linkRemetente . ' compartilhou ' . $tipoStr
                         . ' no seu ' . $linkDestinatario . '.');

        }

        return parent::getMsg();
    }

    public function getUrl()
    {
        if ( null === $this->_url ) {

            $this->setUrl( site_url('/perfil/' . $this->getDestinatario()->getId()) );

        }

        return parent::getUrl();
    }
}
