<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:24
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_Anotacao extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var int
     */
    private $_id;

    /**
     * @var string
     */
    private $_conteudo;

    /**
     * @var WeLearn_Cursos_Conteudo_Pagina
     */
    private $_pagina;

    /**
     * @var WeLearn_Cursos_ParticipacaoCurso
     */
    private $_participacaoCurso;

    /**
     * @param int $id
     * @param string $conteudo
     * @param null $pagina
     * @param null $participacaoCurso
     */
    public function __construct($id = 0,
                                $conteudo = '',
                                WeLearn_Cursos_Conteudo_Pagina $pagina = null,
                                WeLearn_Cursos_ParticipacaoCurso $participacaoCurso = null)
    {
        $dados = array(
            'id' => $id,
            'conteudo' => $conteudo,
            'pagina' => $pagina,
            'participacaoCurso' => $participacaoCurso
        );

        parent::__construct($dados);
    }

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
            'pagina' => $this->getPagina()->toArray(),
            'participacaoCurso' => $this->getParticipacaoCurso()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
