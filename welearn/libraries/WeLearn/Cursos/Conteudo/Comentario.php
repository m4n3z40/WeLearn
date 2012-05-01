<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:22
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_Comentario extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_id;

    /**
     * @var string
     */
    private $_assunto;

    /**
     * @var string
     */
    private $_txtComentario;

    /**
     * @var int
     */
    private $_dataEnvio;

    /**
     * @var int
     */
    private $_dataAlteracao;

    /**
     * @var WeLearn_Cursos_Conteudo_Pagina
     */
    private $_pagina;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @param string $assunto
     */
    public function setAssunto($assunto)
    {
        $this->_assunto = (string)$assunto;
    }

    /**
     * @return string
     */
    public function getAssunto()
    {
        return $this->_assunto;
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
     * @param int $dataAlteracao
     */
    public function setDataAlteracao($dataAlteracao)
    {
        $this->_dataAlteracao = (int)$dataAlteracao;
    }

    /**
     * @return int
     */
    public function getDataAlteracao()
    {
        return $this->_dataAlteracao;
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
     * @param \WeLearn_Cursos_Conteudo_Pagina $pagina
     */
    public function setPagina(WeLearn_Cursos_Conteudo_Pagina $pagina)
    {
        $this->_pagina = $pagina;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Pagina
     */
    public function getPagina()
    {
        return $this->_pagina;
    }

    /**
     * @param string $txtComentario
     */
    public function setTxtComentario($txtComentario)
    {
        $this->_txtComentario = (string)$txtComentario;
    }

    /**
     * @return string
     */
    public function getTxtComentario()
    {
        return $this->_txtComentario;
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
            'assunto' => $this->getAssunto(),
            'txtComentario' => $this->getTxtComentario(),
            'dataEnvio' => $this->getDataEnvio(),
            'dataAlteracao' => $this->getDataAlteracao(),
            'pagina' => is_null($this->_pagina) ? '' : $this->getPagina()->toArray(),
            'criador' => is_null($this->_criador) ? '' : $this->getCriador()->toArray(),
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
            'assunto' => $this->getAssunto(),
            'txtComentario' => $this->getTxtComentario(),
            'dataEnvio' => $this->getDataEnvio(),
            'dataAlteracao' => ( ! $this->_dataAlteracao )
                                ? '' : $this->getDataAlteracao(),
            'pagina' => ($this->_pagina instanceof WeLearn_Cursos_Conteudo_Pagina)
                        ? $this->getPagina()->getId() : '',
            'criador' => ($this->_criador instanceof WeLearn_Usuarios_Usuario)
                        ? $this->getCriador()->getId() : ''
        );
    }
}
