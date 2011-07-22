<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 14:10
 * 
 * Description:
 *
 */
 
abstract class WeLearn_Cursos_Enquetes_StatusEnquete implements WeLearn_Base_IEnum
{
    /**
     * @constant
     */
    const ATIVA = 0;

    /**
     * @constant
     */
    const INATIVA = 1;

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
            case self::ATIVA:
                return 'Enquete Ativa';
            case self::INATIVA:
                return 'Enquete Inativa';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
