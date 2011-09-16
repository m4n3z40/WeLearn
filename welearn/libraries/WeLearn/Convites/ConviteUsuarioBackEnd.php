<?php
/**
 * Created by Thiago Monteiro
 * Date: 26/07/11
 * Time: 18:00
 *
 * Description:
 *
 */

class ConviteUsuarioBackEnd extends WeLearn_Convites_ConviteCadastrado
{
    /**
     * @var int
     **/
    private $_nivelAcesso;


    /**
     * @param int $nivel
     **/
    public function setNivelAcesso($nivel)
    {
        $this->_nivelAcesso = $nivel;
    }

    /**
     * @return int
     **/
    public function getNivelAcesso()
    {
        return $this->_nivelAcesso;
    }

    public function toArray()
    {
        $selfArray = parent::toArray();

        $selfArray = array_merge(
            $selfArray,
            array(
                'nivelAcesso' => $this->getNivelAcesso()
            )
        );

        return $selfArray;
    }
}