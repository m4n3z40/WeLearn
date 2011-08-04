<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 16:21
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Reviews_Resenha extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var int
     */
    private $_id;

    /*
     * string
     */
    private $_conteudo;

    /**
     * @var string
     */
    private $_dataEnvio;

    /**
     * @var int
     */
    private $_qualidade;

    /**
     * @var int
     */
    private $_dificuldade;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var WeLearn_Usuarios_Aluno
     */
    private $_criador;

    public function setConteudo($conteudo)
    {
        $this->_conteudo = (string)$conteudo;
    }

    public function getConteudo()
    {
        return $this->_conteudo;
    }

    /**
     * @param \WeLearn_Usuarios_Aluno $criador
     */
    public function setCriador(WeLearn_Usuarios_Aluno $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_Aluno
     */
    public function getCriador()
    {
        return $this->_criador;
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
     * @param string $dataEnvio
     */
    public function setDataEnvio($dataEnvio)
    {
        $this->_dataEnvio = (string)$dataEnvio;
    }

    /**
     * @return string
     */
    public function getDataEnvio()
    {
        return $this->_dataEnvio;
    }

    /**
     * @param int $dificuldade
     */
    public function setDificuldade($dificuldade)
    {
        $this->_dificuldade = (int)$dificuldade;
    }

    /**
     * @return int
     */
    public function getDificuldade()
    {
        return $this->_dificuldade;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $qualidade
     */
    public function setQualidade($qualidade)
    {
        $this->_qualidade = (int)$qualidade;
    }

    /**
     * @return int
     */
    public function getQualidade()
    {
        return $this->_qualidade;
    }

    /**
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'conteudo' => $this->getConteudo(),
            'dataEnvio' => $this->getDataEnvio(),
            'qualidade' => $this->getQualidade(),
            'dificuldade' => $this->getDificuldade(),
            'curso' => $this->getCurso()->toArray(),
            'criador' => $this->getCriador()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
