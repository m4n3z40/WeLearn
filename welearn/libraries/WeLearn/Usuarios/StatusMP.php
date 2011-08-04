<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 14:18
 *
 * Description:
 *
 */

/**
 * @throws WeLearn_Base_CodigoEnumIncorretoException
 */
abstract class WeLearn_Usuarios_StatusMP implements WeLearn_Base_IEnum
{

    /**
     * Indicador do Status da Mensagem Pessoal Nova
     *
     * @constant
     */
    const NOVO = 0;

    /**
     * Indicador do Status da Menaagem Pessoal Lida
     *
     * @constant
     */
    const LIDO = 1;

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
            case self::NOVO:
                return 'Mensagem Pessoal Nova';
            case self::LIDO:
                return 'Mensagem Pessoal Lida';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
