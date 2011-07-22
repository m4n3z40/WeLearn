<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:34
 *
 * Description:
 *
 */

class WeLearn_Cursos_ImagemCurso extends WeLearn_DTO_AbstractDTO
{

    /**
     * @var string
     */
    private $_url;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @param string $url
     * @param null|WeLearn_Cursos_Curso $curso
     */
    public function __construct($url = '', WeLearn_Cursos_Curso $curso = null)
    {
        $dados = array(
            'url' => $url,
            'curso' => $curso
        );

        parent::__construct($dados);
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

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = (string)$url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }
}
