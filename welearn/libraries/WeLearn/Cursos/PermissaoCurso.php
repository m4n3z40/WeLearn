<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 17:34
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_PermissaoCurso implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const LIVRE = 0;

    /**
     * @constant
     */
    const RESTRITO = 1;

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
                return 'Livre';
            case self::RESTRITO;
                return 'Restrito';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
