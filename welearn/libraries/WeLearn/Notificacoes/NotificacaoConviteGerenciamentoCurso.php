<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoConviteGerenciamentoCurso extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @param \WeLearn_Cursos_Curso $curso
     */
    public function setCurso(WeLearn_Cursos_Curso $curso)
    {
        $this->_curso = $curso;
    }

    /**
     * @return \WeLearn_Cursos_Curso
     */
    public function getCurso()
    {
        return $this->_curso;
    }

    public function getMsg()
    {
        if ( null === $this->_msg ) {

            $linkCurso = anchor(
                '/curso/' . $this->getCurso()->getId(),
                $this->getCurso()->getNome()
            );

            $linkConvite = anchor(
                '/curso/meus_convites',
                'convite'
            );

            $this->setMsg('Você recebeu um ' . $linkConvite
                          . ' para fazer parte do corpo de gerenciadores do curso '
                          . $linkCurso . '.');

        }

        return parent::getMsg();
    }

    public function getUrl()
    {
        if ( null === $this->_url ) {

            $this->setUrl( site_url('/curso/meus_convites') );

        }

        return parent::getUrl();
    }
}
