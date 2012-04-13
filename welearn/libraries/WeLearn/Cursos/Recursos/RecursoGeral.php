<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 03/04/12
 * Time: 17:21
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Cursos_Recursos_RecursoGeral extends WeLearn_Cursos_Recursos_Recurso
{
    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    public function __construct(array $dados = null)
    {
        parent::__construct($dados);

        $this->setTipo( WeLearn_Cursos_Recursos_TipoRecurso::GERAL );
    }

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

    public function toArray()
    {
        $selfArray = parent::toArray();

        if ( $this->_curso instanceof WeLearn_Cursos_Curso ) {
            $selfArray['curso'] = $this->getCurso()->toArray();
        }

        return $selfArray;
    }

    public function toCassandra()
    {
        $selfArray = parent::toCassandra();

        if ( $this->_curso instanceof WeLearn_Cursos_Curso ) {
            $selfArray['curso'] = $this->getCurso()->getId();
        }

        return $selfArray;
    }
}
