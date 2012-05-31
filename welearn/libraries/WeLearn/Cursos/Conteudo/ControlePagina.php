<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:26
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_ControlePagina extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var float
     */
    private $_tempoVisualizacao;

    /**
     * @var WeLearn_Cursos_ParticipacaoCurso
     */
    private $_participacaoCurso;

    /**
     * @var WeLearn_Cursos_Conteudo_Pagina
     */
    private $_pagina;

    /**
     * @var int
     */
    private $_status;

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
     * @param \WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     */
    public function setParticipacaoCurso(WeLearn_Cursos_ParticipacaoCurso $participacaoCurso)
    {
        $this->_participacaoCurso = $participacaoCurso;
    }

    /**
     * @return \WeLearn_Cursos_ParticipacaoCurso
     */
    public function getParticipacaoCurso()
    {
        return $this->_participacaoCurso;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->_status = (int)$status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param float $tempoVisualizacao
     */
    public function setTempoVisualizacao($tempoVisualizacao)
    {
        $this->_tempoVisualizacao = (float)$tempoVisualizacao;
    }

    /**
     * @return float
     */
    public function getTempoVisualizacao()
    {
        return $this->_tempoVisualizacao;
    }

    /**
     * @return void
     */
    public function acessar()
    {
        $this->setStatus( WeLearn_Cursos_Conteudo_StatusConteudo::ACESSANDO );
    }

    /**
     * @return void
     */
    public function bloquear()
    {
        $this->setStatus( WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO );
    }

    /**
     * @return void
     */
    public function finalizar()
    {
        $this->setStatus( WeLearn_Cursos_Conteudo_StatusConteudo::ACESSADO );
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
            'tempoVisualizacao' => $this->getTempoVisualizacao(),
            'participacaoCurso' => $this->getParticipacaoCurso()->toArray(),
            'pagina' => $this->getPagina()->toArray(),
            'status' => $this->getStatus(),
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
        $UUID = UUID::import( $this->getPagina()->getId() )->bytes;

        return array(
            $UUID => $this->getStatus() . '|' . $this->getTempoVisualizacao()
        );
    }


}