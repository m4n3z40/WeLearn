<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 15:46
 * 
 * Description:
 *
 */
 
abstract class WeLearn_Cursos_Foruns_StatusForum implements WeLearn_Base_IEnum
{
    /**
     * @constant
     */
    const ATIVO = 0;

    /**
     * @constant
     */
    const INATIVO = 1;

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao($codigo)
    {
        switch($codigo) {
            case self::ATIVO:
                return 'Fórum Ativo';
            case self::INATIVO:
                return 'Fórum Inativo';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
