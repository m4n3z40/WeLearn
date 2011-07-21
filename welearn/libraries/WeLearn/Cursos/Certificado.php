<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:44
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Certificado extends WeLearn_DTO_AbstractDTO {

    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_descricao;

    /**
     * @var string
     */
    private $_urlCertificado;

    /**
     * @var boolean
     */
    private $_ativo;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @param boolean $ativo
     */
    public function setAtivo( $ativo )
    {
        $this->_ativo = (boolean) $ativo;
    }

    /**
     * @return boolean
     */
    public function isAtivo()
    {
        return $this->_ativo;
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
     * @param string $descricao
     */
    public function setDescricao( $descricao )
    {
        $this->_descricao = (string) $descricao;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->_descricao;
    }

    /**
     * @param int $id
     */
    public function setId( $id )
    {
        $this->_id = (int) $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param string $urlCertificado
     */
    public function setUrlCertificado( $urlCertificado )
    {
        $this->_urlCertificado = (string) $urlCertificado;
    }

    /**
     * @return string
     */
    public function getUrlCertificado()
    {
        return $this->_urlCertificado;
    }
}
