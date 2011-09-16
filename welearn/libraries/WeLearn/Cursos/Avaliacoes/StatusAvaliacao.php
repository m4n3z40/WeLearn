<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 23:04
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_Avaliacoes_StatusAvaliacao implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const LIBERADA = 0;

    /**
     * @constant
     */
    const BLOQUEADA = 1;

    /**
     * @constant
     */
    const DESATIVADA = 2;

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
            case self::LIBERADA:
                return 'Avaliação Liberada';
            case self::BLOQUEADA:
                return 'Avaliação Bloqueada';
            case self::DESATIVADA:
                return 'Avaliação Desativada';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
