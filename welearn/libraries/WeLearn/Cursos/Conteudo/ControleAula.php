<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:25
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_ControleAula extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var WeLearn_Cursos_Conteudo_Aula
     */
    private $_aula;

    /**
     * @var WeLearn_Cursos_ParticipacaoCurso
     */
    private $_participacaoCurso;

    /**
     * @var int
     */
    private $_status;

    /**
     * @param null|WeLearn_Cursos_Conteudo_Aula $aula
     * @param null|WeLearn_Cursos_ParticipacaoCurso $participacaoCurso
     * @param int $status
     */
    public function __construct(WeLearn_Cursos_Conteudo_Aula $aula = null,
                                WeLearn_Cursos_ParticipacaoCurso $participacaoCurso = null,
                                $status = WeLearn_Cursos_Conteudo_StatusConteudo::BLOQUEADO)
    {
        $dados = array(
            'aula' => $aula,
            'participacaoCurso' => $participacaoCurso,
            'status' => $status
        );

        parent::__construct( $dados );
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
     * Converte os dados das propriedades do objeto para uma relação 'propriedade => valor'
     * em um array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'aula' => $this->getAula()->toArray(),
            'participacaoCurso' => $this->getParticipacaoCurso()->toArray(),
            'status' => $this->getStatus(),
            'persistido' => $this->isPersistido()
        );
    }
}
