<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 19:16
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_ListaVisitas extends WeLearn_DTO_AbstractDTO {

    /**
     * @var string
     */
    private $_dataVisita;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_visitante;

    /**
     * @param string $dataVisita
     * @param null|WeLearn_Cursos_Curso $curso
     * @param null|WeLearn_Usuarios_Usuario $visitante
     */
    public function __construct( $dataVisita = '',
                                 WeLearn_Cursos_Curso $curso = null,
                                 WeLearn_Usuarios_Usuario $visitante = null )
    {
        $dados = array(
            'dataVisita' => $dataVisita,
            'curso' => $curso,
            'visitante' => $visitante
        );

        parent::__construct( $dados );
    }

    /**
     * @param \WeLearn_Cursos_Curso $curso
     */
    public function setCurso( WeLearn_Cursos_Curso $curso )
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

    /**
     * @param string $dataVisita
     */
    public function setDataVisita( $dataVisita )
    {
        $this->_dataVisita = (string) $dataVisita;
    }

    /**
     * @return string
     */
    public function getDataVisita()
    {
        return $this->_dataVisita;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $visitante
     */
    public function setVisitante( WeLearn_Usuarios_Usuario $visitante )
    {
        $this->_visitante = $visitante;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getVisitante()
    {
        return $this->_visitante;
    }
}
