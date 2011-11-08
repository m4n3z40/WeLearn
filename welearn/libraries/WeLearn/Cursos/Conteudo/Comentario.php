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
     * @var int
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
     * @var string
     */
    private $_dataEnvio;

    /**
     * @var string
     */
    private $_dataAlteracao;

    /**
     * @var WeLearn_Cursos_Conteudo_Aula
     */
    private $_aula;

    /**
     * @var WeLearn_Cursos_Conteudo_Pagina
     */
    private $_pagina;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

    /**
     * @var WeLearn_Cursos_Conteudo_Comentario
     */
    private $_respostaDe;

    /**
     * @var array
     */
    private $_respostas;

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
     * @param \WeLearn_Cursos_Conteudo_Aula $aula
     */
    public function setAula(WeLearn_Cursos_Conteudo_Aula $aula)
    {
        $this->_aula = $aula;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Aula
     */
    public function getAula()
    {
        return $this->_aula;
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
     * @param \WeLearn_Cursos_Conteudo_Comentario $respostaDe
     */
    public function setRespostaDe(WeLearn_Cursos_Conteudo_Comentario $respostaDe)
    {
        $this->_respostaDe = $respostaDe;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Comentario
     */
    public function getRespostaDe()
    {
        return $this->_respostaDe;
    }

    /**
     * @param array $respostas
     */
    public function setRespostas(array $respostas)
    {
        $this->_respostas = $respostas;
    }

    /**
     * @return array
     */
    public function getRespostas()
    {
        return $this->_respostas;
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
        $respostas = array();
        if (!is_null($this->_respostas)) {
            foreach ($respostas as $resposta) {
                $respostas[] = $resposta->toArray();
            }
        }

        return array(
            'id' => $this->getId(),
            'assunto' => $this->getAssunto(),
            'txtComentario' => $this->getTxtComentario(),
            'dataEnvio' => $this->getDataEnvio(),
            'dataAlteracao' => $this->getDataAlteracao(),
            'aula' => is_null($this->_aula) ? '' : $this->getAula()->toArray(),
            'pagina' => is_null($this->_pagina) ? '' : $this->getPagina()->toArray(),
            'criador' => $this->getCriador()->toArray(),
            'respostaDe' => $this->getRespostaDe()->getId(),
            'respostas' => $respostas,
            'persistido' => $this->isPersistido()
        );
    }
}
