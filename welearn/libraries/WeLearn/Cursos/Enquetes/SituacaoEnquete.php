<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 14:12
 * 
 * Description:
 *
 */
 
abstract class WeLearn_Cursos_Enquetes_SituacaoEnquete implements WeLearn_Base_IEnum
{
    /**
     * @constant
     */
    const ABERTA = 0;

    /**
     * @constant
     */
    const FECHADA = 1;

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
            case self::ABERTA:
                return 'Enquete Aberta';
            case self::FECHADA:
                return 'Enquete Fechada';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
