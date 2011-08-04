<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 13:17
 *
 * Description:
 *
 */

/**
 * @throws WeLearn_Base_CodigoEnumIncorretoException
 */
abstract class WeLearn_Usuarios_PrivacidadeConvites implements WeLearn_Base_IEnum
{

    /**
     * Indicador de Convites Livres
     *
     * @constant
     */
    const LIVRE = 0;

    /**
     * Indicador de Convites restritos somente à amigos
     *
     * @constant
     */
    const SO_AMIGOS = 1;

    /**
     * Indicador de Convites Desabilitados
     *
     * @constant
     */
    const DESABILITADO = 2;

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao($codigo)
    {
        switch ($codigo) {
            case self::LIVRE:
                return 'Convites Livres';
            case self::SO_AMIGOS;
                return 'Convites restritos somente à amigos';
            case self::DESABILITADO;
                return 'Convites Desabilitados';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
