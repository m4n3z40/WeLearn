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
     * @param int $tempoVisualizacao
     * @param null|WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param null|WeLearn_Cursos_Conteudo_Pagina $pagina
     * @param int $status
     */
    public function __construct($tempoVisualizacao = 0,
                                WeLearn_Cursos_ParticipacaoCurso $participacaoCurso = null,
                                WeLearn_Cursos_Conteudo_Pagina $pagina = null,
                                $status = WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO)
    {
        $dados = array(
            'tempoVisualizacao' => $tempoVisualizacao,
            'participacaoCurso' => $participacaoCurso,
            'pagina' => $pagina,
            'status' => $status
        );

        parent::__construct($dados);
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
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function bloquear()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function finalizar()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function registrarInicioVisualizacao()
    {
        //@TODO: Implementar este método!!
    }

    /**
     * @return void
     */
    public function registrarFimVisualizacao()
    {
        //@TODO: Implementar este método!!
    }
}