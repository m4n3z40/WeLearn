<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 19:16
 *
 * Description:
 *
 */

class WeLearn_Cursos_ListaBanidos extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_dataInclusao;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_banido;

    /**
     * @param string $dataInclusao
     * @param null|WeLearn_Cursos_Curso $curso
     * @param null|WeLearn_Usuarios_Usuario $banido
     */
    public function __construct($dataInclusao = '',
        WeLearn_Cursos_Curso $curso = null,
        WeLearn_Usuarios_Usuario $banido = null)
    {
        $dados = array(
            'dataInclusao' => $dataInclusao,
            'curso' => $curso,
            'banido' => $banido
        );

        parent::__construct($dados);
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $banido
     */
    public function setBanido(WeLearn_Usuarios_Usuario $banido)
    {
        $this->_banido = $banido;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getBanido()
    {
        return $this->_banido;
    }

    /**
     * @param \WeLearn_Cursos_Curso $curso
     */
    public function setCurso(WeLearn_Cursos_Curso $curso)
    {
        $this->_curso = $curso;
    }

    /**
     * @return \WeLearn_Cursos_Curso
     */
    public function getCurso()
    {
        return $this->_curso;
    }

    /**
     * @param string $dataInclusao
     */
    public function setDataInclusao($dataInclusao)
    {
        $this->_dataInclusao = (string)$dataInclusao;
    }

    /**
     * @return string
     */
    public function getDataInclusao()
    {
        return $this->_dataInclusao;
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
            'dataInclusao' => $this->getDataInclusao(),
            'curso' => $this->getCurso()->toArray(),
            'banido' => $this->getBanido()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
