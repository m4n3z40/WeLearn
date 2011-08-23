<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 18:08
 *
 * Description:
 *
 */

class WeLearn_Cursos_SugestaoCurso extends WeLearn_Cursos_CursoBasico
{

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_criador;

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
     * @param WeLearn_Cursos_Curso $curso
     * @return void
     */
    public function registrarCriacaoCurso(WeLearn_Cursos_Curso $curso)
    {
        //@TODO: Implementar este método!
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'criador' => $this->getCriador()->toArray()
            )
        );

        return $selfArray;
    }
}
