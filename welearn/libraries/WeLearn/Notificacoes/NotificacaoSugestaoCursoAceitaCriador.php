<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoSugestaoCursoAceitaCriador extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Cursos_SugestaoCurso
     */
    private $_sugestao;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_cursoCriado;

    /**
     * @param \WeLearn_Cursos_SugestaoCurso $sugestao
     */
    public function setSugestao(WeLearn_Cursos_SugestaoCurso $sugestao)
    {
        $this->_sugestao = $sugestao;
    }

    /**
     * @return \WeLearn_Cursos_SugestaoCurso
     */
    public function getSugestao()
    {
        return $this->_sugestao;
    }

    /**
     * @param \WeLearn_Cursos_Curso $cursoCriado
     */
    public function setCursoCriado(WeLearn_Cursos_Curso $cursoCriado)
    {
        $this->_cursoCriado = $cursoCriado;
    }

    /**
     * @return \WeLearn_Cursos_Curso
     */
    public function getCursoCriado()
    {
        return $this->_cursoCriado;
    }

    public function getMsg()
    {
        if ( null === $this->_msg ) {

            $linkSugestao = anchor(
                '/curso/sugestao',
                $this->getSugestao()->getNome()
            );

            $linkCurso = anchor(
                '/curso/' . $this->getCursoCriado()->getId(),
                $this->getCursoCriado()->getNome()
            );

            $this->setMsg(
                'A sugestão de curso ' . $linkSugestao
              . ' que você criou, gerou o curso ' . $linkCurso . '.'
            );

        }

        return parent::getMsg();
    }

    public function getUrl()
    {
        if ( null === $this->_url ) {

            $this->setUrl( site_url('/curso/' . $this->getCursoCriado()->getId()) );

        }

        return parent::getUrl();
    }
}
