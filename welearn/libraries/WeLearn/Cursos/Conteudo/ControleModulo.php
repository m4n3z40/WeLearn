<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:25
 *
 * Description:
 *
 */

class WeLearn_Cursos_Conteudo_ControleModulo extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var WeLearn_Cursos_Conteudo_Modulo
     */
    private $_modulo;

    /**
     * @var WeLearn_Cursos_ParticipacaoCurso
     */
    private $_participacaoCurso;

    /**
     * @var int
     */
    private $_status;

    /**
     * @param \WeLearn_Cursos_Conteudo_Modulo $modulo
     */
    public function setModulo(WeLearn_Cursos_Conteudo_Modulo $modulo)
    {
        $this->_modulo = $modulo;
    }

    /**
     * @return \WeLearn_Cursos_Conteudo_Modulo
     */
    public function getModulo()
    {
        return $this->_modulo;
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
        $this->setStatus( WeLearn_Cursos_Conteudo_StatusConteudo::FINALIZADO );
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
            'modulo' => $this->getModulo()->toArray(),
            'participacaoCurso' => $this->getParticipacaoCurso()->toArray(),
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
        $UUID = UUID::import( $this->getModulo()->getId() )->bytes;

        return array(
            $UUID => $this->getStatus()
        );
    }
}