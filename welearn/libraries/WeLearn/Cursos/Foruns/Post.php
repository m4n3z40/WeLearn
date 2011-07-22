<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 15:48
 * 
 * Description:
 *
 */
 
class WeLearn_Cursos_Foruns_Post extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_titulo;

    /**
     * @var string
     */
    private $_conteudo;

    /**
     * @var string
     */
    private $_dataCriacao;

    /**
     * @var string
     */
    private $_dataAlteracao;

    /**
     * @var WeLearn_Cursos_Foruns_Forum
     */
    private $_forum;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

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
     * @param string $dataAlteracao
     */
    public function setDataAlteracao($dataAlteracao)
    {
        $this->_dataAlteracao = (string)$dataAlteracao;
    }

    /**
     * @return string
     */
    public function getDataAlteracao()
    {
        return $this->_dataAlteracao;
    }

    /**
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->_dataCriacao = (string)$dataCriacao;
    }

    /**
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->_dataCriacao;
    }

    /**
     * @param \WeLearn_Cursos_Foruns_Forum $forum
     */
    public function setForum(WeLearn_Cursos_Foruns_Forum $forum)
    {
        $this->_forum = $forum;
    }

    /**
     * @return \WeLearn_Cursos_Foruns_Forum
     */
    public function getForum()
    {
        return $this->_forum;
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
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->_titulo = (string)$titulo;
    }

    /**
     * @return string
     */
    public function getTitulo()
    {
        return $this->_titulo;
    }
}
