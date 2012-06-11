<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 6/9/12
 * Time: 2:44 AM
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Notificacoes_NotificacaoSugestaoCursoAceita extends WeLearn_Notificacoes_Notificacao
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
        //TODO: Implementar msg de notificação.
        return parent::getMsg();
    }

    public function getUrl()
    {
        //TODO: Implementar url da notificacao.
        return parent::getUrl();
    }
}
