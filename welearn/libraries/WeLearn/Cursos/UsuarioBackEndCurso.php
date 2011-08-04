<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 19:02
 *
 * Description:
 *
 */

class WeLearn_Cursos_UsuarioBackEndCurso extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var string
     */
    private $_membroDesde;

    /**
     * @var int
     */
    private $_nivelAcesso;

    /**
     * @var WeLearn_Usuarios_UsuarioBackEnd
     */
    private $_usuarioBackEnd;

    /**
     * @var WeLearn_Cursos_Curso
     */
    private $_curso;

    /**
     * @param string $membroDesde
     * @param int $nivelAcesso
     * @param null|WeLearn_Usuarios_UsuarioBackEnd $usuarioBackEnd
     * @param null|WeLearn_Cursos_Curso $curso
     */
    public function __construct($membroDesde = '',
        $nivelAcesso = WeLearn_Usuarios_NivelAcesso::MODERADOR,
        WeLearn_Usuarios_UsuarioBackEnd $usuarioBackEnd = null,
        WeLearn_Cursos_Curso $curso = null)
    {
        $dados = array(
            'membroDesde' => $membroDesde,
            'nivelAcesso' => $nivelAcesso,
            'usuarioBackEnd' => $usuarioBackEnd,
            'curso' => $curso
        );

        parent::__construct($dados);
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
     * @param string $membroDesde
     */
    public function setMembroDesde($membroDesde)
    {
        $this->_membroDesde = (string)$membroDesde;
    }

    /**
     * @return string
     */
    public function getMembroDesde()
    {
        return $this->_membroDesde;
    }

    /**
     * @param int $nivelAcesso
     */
    public function setNivelAcesso($nivelAcesso)
    {
        $this->_nivelAcesso = (int)$nivelAcesso;
    }

    /**
     * @return int
     */
    public function getNivelAcesso()
    {
        return $this->_nivelAcesso;
    }

    /**
     * @param \WeLearn_Usuarios_UsuarioBackEnd $usuarioBackEnd
     */
    public function setUsuarioBackEnd(WeLearn_Usuarios_UsuarioBackEnd $usuarioBackEnd)
    {
        $this->_usuarioBackEnd = $usuarioBackEnd;
    }

    /**
     * @return \WeLearn_Usuarios_UsuarioBackEnd
     */
    public function getUsuarioBackEnd()
    {
        return $this->_usuarioBackEnd;
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
            'menbroDesde' => $this->getMembroDesde(),
            'nivelAcesso' => $this->getNivelAcesso(),
            'usuarioBackEnd' => $this->getUsuarioBackEnd()->toArray(),
            'curso' => $this->getCurso()->toArray(),
            'persistido' => $this->isPersistido()
        );
    }
}
