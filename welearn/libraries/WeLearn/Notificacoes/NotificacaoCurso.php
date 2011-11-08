<?php

class WeLearn_Notificacoes_NotificacaoCurso extends WeLearn_Notificacoes_Notificacao
{
    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_remetente;

    /**
     * @param \WeLearn_Cursos_Curso $remetente
     */
    public function setRemetente(WeLearn_Cursos_Curso $remetente)
    {
        $this->_remetente = $remetente;
    }

    /**
     * @return \WeLearn_Cursos_Curso
     */
    public function getRemetente()
    {
        return $this->_remetente;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'remetente' => $this->getRemetente()->toArray()
            )
        );

        return $selfArray;
    }
}