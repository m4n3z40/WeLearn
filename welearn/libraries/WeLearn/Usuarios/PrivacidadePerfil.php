<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 03:48
 *
 * Description:
 *
 */

/**
 * @throws WeLearn_Base_CodigoEnumIncorretoException
 */
abstract class WeLearn_Usuarios_PrivacidadePerfil implements WeLearn_Base_IEnum
{

    /**
     * Indicador de Perfil Privado
     *
     * @constant
     */
    const PRIVADO = 0;

    /**
     * Indicador de perfil Público
     *
     * @constant
     */
    const PUBLICO = 1;

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     * @static
     */
    public static function getDescricao($codigo)
    {
        switch ($codigo) {
            case self::PRIVADO:
                return 'Perfil Privado';
            case self::PUBLICO:
                return 'Perfil Público';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
