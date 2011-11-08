<?php
/**
 * Created by Thiago Monteiro
 * Date: 26/07/11
 * Time: 18:10
 *
 * Description:
 *
 */

class WeLearn_Convites_ConviteVisitanteCurso extends WeLearn_Convites_ConviteVisitante
{
    /**
     * @var WeLearn_Cursos_Curso
     **/
    private $_paraCurso;

    /**
     * @param WeLearn_Cursos_Curso $paraCurso
     **/
    public function setParaCurso(WeLearn_Cursos_Curso $paraCurso)
    {
        $this->_paraCurso = $paraCurso;
    }

    /**
     * @return WeLearn_Cursos_Curso
     **/
    public function getParaCurso()
    {
        return $this->_paraCurso;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'paraCurso' => $this->getParaCurso()->toArray()
            )
        );
    }
}