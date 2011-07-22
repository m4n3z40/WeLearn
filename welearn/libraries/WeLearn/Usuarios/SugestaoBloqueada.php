<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 15:43
 *
 * Description:
 *
 */

class WeLearn_Usuarios_SugestaoBloqueada extends WeLearn_DTO_AbstractDTO
{
    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_dono;

    /**
     * @var WeLearn_Usuarios_Usuario
     */
    private $_bloqueado;

    /**
     * @param null|WeLearn_Usuarios_Usuario $dono
     * @param null|WeLearn_Usuarios_Usuario $bloqueado
     */
    public function __construct(WeLearn_Usuarios_Usuario $dono = null,
        WeLearn_Usuarios_Usuario $bloqueado = null)
    {
        $dados = array(
            'dono' => $dono,
            'bloqueado' => $bloqueado
        );

        parent::__construct($dados);
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $bloqueado
     */
    public function setBloqueado(WeLearn_Usuarios_Usuario $bloqueado)
    {
        $this->_bloqueado = $bloqueado;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getBloqueado()
    {
        return $this->_bloqueado;
    }

    /**
     * @param \WeLearn_Usuarios_Usuario $dono
     */
    public function setDono(WeLearn_Usuarios_Usuario $dono)
    {
        $this->_dono = $dono;
    }

    /**
     * @return \WeLearn_Usuarios_Usuario
     */
    public function getDono()
    {
        return $this->_dono;
    }
}