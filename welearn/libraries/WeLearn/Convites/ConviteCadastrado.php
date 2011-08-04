<?php
/**
 * Created by Thiago Monteiro
 * Date: 26/07/11
 * Time: 17:16
 *
 * Description:
 *
 */

class WeLearn_Convites_ConviteCadastrado extends WeLearn_Convites_ConviteBasico
{
    /**
     * @var WeLearn_Usuarios_Usuario
     **/
    protected $_destinatario;

    /**
     * @var WeLearn_Cursos_Curso
     **/
    protected $_paraCurso;

    /**
     * @param WeLearn_Usuarios_Usuario $destinatario
     **/
    public function setDestinatario(WeLearn_Usuarios_Usuario $destinatario)
    {
        $this->_destinatario = $destinatario;
    }

    /**
     * @return WeLearn_Usuarios_Usuario
     **/
    public function getDestinatario()
    {
        return $this->_destinatario;
    }

    /**
     * @param WeLearn_Cursos_Curso $paraCurso
     **/
    public function setParaCurso(WeLearn_Cursos_Curso $paraCurso)
    {
        $this->_paraCurso = $paraCurso;
    }

    /**
     * @return WeLearn_Cursos_Curso
     **/
    public function getParaCurso()
    {
        return $this->_paraCurso;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'destinatario' => $this->getDestinatario()->toArray(),
                'paraCurso' => $this->getParaCurso()->toArray()
            )
        );

        return $selfArray;
    }
}