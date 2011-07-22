<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 23:05
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_Avaliacoes_SituacaoAvaliacao implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const NAO_INICIADA = 0;

    /**
     * @constant
     */
    const APROVADO = 1;

    /**
     * @constant
     */
    const REPROVADO = 2;

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao($codigo)
    {
        switch ($codigo)
        {
            case self::NAO_INICIADA:
                return 'Avaliação não iniciada';
            case self::APROVADO:
                return 'Aprovado na avaliação';
            case self::REPROVADO:
                return 'Reprovado na avaliação';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
