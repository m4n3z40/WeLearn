<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoResenhaRespondida extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Cursos_Reviews_Resenha
     */
    private $_resenha;

    /**
     * @param \WeLearn_Cursos_Reviews_Resenha $resenha
     */
    public function setResenha(WeLearn_Cursos_Reviews_Resenha $resenha)
    {
        $this->_resenha = $resenha;
    }

    /**
     * @return \WeLearn_Cursos_Reviews_Resenha
     */
    public function getResenha()
    {
        return $this->_resenha;
    }

    public function getMsg()
    {
        if ( null === $this->_msg ) {

            $linkResenha = anchor(
                '/curso/review/' . $this->getResenha()->getCurso()->getId(),
                'avaliação'
            );

            $linkCurso = anchor(
                '/curso/' . $this->getResenha()->getCurso()->getId(),
                $this->getResenha()->getCurso()->getNome()
            );

            $linkGerenciador = anchor(
                '/perfil/' . $this->getResenha()->getResposta()->getCriador()->getId(),
                $this->getResenha()->getResposta()->getCriador()->getNome()
            );

            $this->setMsg('Sua ' . $linkResenha . ' do curso ' . $linkCurso
                . ' foi respondida pelo Gerenciador ' . $linkGerenciador . '.');
        }

        return parent::getMsg();
    }

    public function getUrl()
    {
        if ( null === $this->_url ) {

            $this->setUrl( site_url('/curso/review/' . $this->getResenha()->getCurso()->getId()) );

        }

        return parent::getUrl();
    }
}
