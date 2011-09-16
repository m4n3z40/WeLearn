<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:14
 *
 * Description:
 *
 */

class WeLearn_Cursos_VotoSugestaoCurso extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_dataVoto;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_votante;

    /**
     * @var WeLearn_Cursos_SugestaoCurso
     */
    private $_sugestaoCurso;

    /**
     * @param string $dataVoto
     * @param null|WeLearn_Usuarios_Usuario $votante
     * @param null|WeLearn_Cursos_SugestaoCurso $sugestaoCurso
     */
    public function __construct($dataVoto = '',
        WeLearn_Usuarios_Usuario $votante = null,
        WeLearn_Cursos_SugestaoCurso $sugestaoCurso = null)
    {
        $dados = array(
            'dataVoto' => $dataVoto,
            'votante' => $votante,
            'sugestaoCurso' => $sugestaoCurso
        );

        parent::__construct($dados);
    }

    /**
     * @param string $dataVoto
     */
    public function setDataVoto($dataVoto)
    {
        $this->_dataVoto = (string)$dataVoto;
    }

    /**
     * @return string
     */
    public function getDataVoto()
    {
        return $this->_dataVoto;
    }

    /**
     * @param \WeLearn_Cursos_SugestaoCurso $sugestaoCurso
     */
    public function setSugestaoCurso(WeLearn_Cursos_SugestaoCurso $sugestaoCurso)
    {
        $this->_sugestaoCurso = $sugestaoCurso;
    }

    /**
     * @return \WeLearn_Cursos_SugestaoCurso
     */
    public function getSugestaoCurso()
    {
        return $this->_sugestaoCurso;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $votante
     */
    public function setVotante(WeLearn_Usuarios_Usuario $votante)
    {
        $this->_votante = $votante;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getVotante()
    {
        return $this->_votante;
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
            'dataVoto' => $this->getDataVoto(),
            'votante' => $this->getVotante()->toArray(),
            'sugestaoCurso' => $this->getSugestaoCurso()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}