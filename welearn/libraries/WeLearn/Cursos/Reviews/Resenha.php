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
     * @var string
     */
    private $_id;

    /*
     * string
     */
    private $_conteudo;

    /**
     * @var int
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
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @var WeLearn_Cursos_Reviews_RespostaResenha
     */
    private $_resposta;

    /**
     * @param string $conteudo
     */
    public function setConteudo($conteudo)
    {
        $this->_conteudo = (string)$conteudo;
    }

    /**
     * @return string
     */
    public function getConteudo()
    {
        return $this->_conteudo;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $criador
     */
    public function setCriador(WeLearn_Usuarios_Usuario $criador)
    {
        $this->_criador = $criador;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
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
     * @param int $dataEnvio
     */
    public function setDataEnvio($dataEnvio)
    {
        $this->_dataEnvio = (int)$dataEnvio;
    }

    /**
     * @return int
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
     * @param string $id
     */
    public function setId($id)
    {
        $this->_id = (string)$id;
    }

    /**
     * @return string
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
     * @param \WeLearn_Cursos_Reviews_RespostaResenha $resposta
     */
    public function setResposta(WeLearn_Cursos_Reviews_RespostaResenha $resposta)
    {
        $this->_resposta = $resposta;
    }

    /**
     * @return \WeLearn_Cursos_Reviews_RespostaResenha
     */
    public function getResposta()
    {
        return $this->_resposta;
    }

    /**
     *
     */
    public function removerResposta()
    {
        $this->_resposta = null;
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
            'curso' => ( $this->_curso instanceof WeLearn_Cursos_Curso )
                       ? $this->getCurso()->toArray() : '',
            'criador' => ( $this->_criador instanceof WeLearn_Usuarios_Usuario )
                         ? $this->getCriador()->toArray() : '',
            'resposta' => ( $this->_resposta instanceof WeLearn_Cursos_Reviews_RespostaResenha )
                          ? $this->getResposta()->toArray() : '',
            'persistido' => $this->isPersistido()
        );
    }

    /**
     * Converte os dados das propriedades do objeto em um array para ser persistido no BD Cassandra
     *
     * @return array
     */
    public function toCassandra()
    {
        return array(
            'id' => $this->getId(),
            'conteudo' => $this->getConteudo(),
            'dataEnvio' => $this->getDataEnvio(),
            'qualidade' => $this->getQualidade(),
            'dificuldade' => $this->getDificuldade(),
            'curso' => ( $this->_curso instanceof WeLearn_Cursos_Curso )
                       ? $this->getCurso()->getId() : '',
            'resposta' => ( $this->_resposta instanceof WeLearn_Cursos_Reviews_RespostaResenha )
                          ? $this->getResposta()->getResenhaId() : '',
            'criador' => ( $this->_criador instanceof WeLearn_Usuarios_Usuario )
                         ? $this->getCriador()->getId() : ''
        );
    }

    public function toMySQL()
    {
        return array(
            'id' => $this->getId(),
            'curso_id' => ( $this->_curso instanceof WeLearn_Cursos_Curso )
                          ? $this->getCurso()->getId() : '',
            'qualidade' => $this->getQualidade(),
            'dificuldade' => $this->getDificuldade()
        );
    }
}
