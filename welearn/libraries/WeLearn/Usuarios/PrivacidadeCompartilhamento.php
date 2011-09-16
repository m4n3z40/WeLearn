<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 13:23
 *
 * Description:
 *
 */

/**
 * @throws WeLearn_Base_CodigoEnumIncorretoException
 */
abstract class WeLearn_Usuarios_PrivacidadeCompartilhamento implements WeLearn_Base_IEnum
{

    /**
     * Indicador de privacidade de Compartilhamento Habilitado
     *
     * @constant
     */
    const HABILITADO = 0;

    /**
     * Indicador de privacidade de Compartilhamento Desabilitado
     *
     * @constant
     */
    const DESABILITADO = 1;

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
            case self::HABILITADO:
                return 'Compartilhamento de Conteúdos Habilitado';
            case self::DESABILITADO:
                return 'Compartilhamento de Conteúdos Desabilitado';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
