<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 12:58
 * 
 * Description:
 *
 */

/**
 * @throws WeLearn_Base_CodigoEnumIncorretoException
 */
abstract class WeLearn_Usuarios_PrivacidadeMP implements WeLearn_Base_IEnum{

    /**
     * Indicador de Mensagens Pessoais Livres
     *
     * @constant
     */
    const LIVRE = 0;

    /**
     * Indicador de Mensagens Pessoais restritas somente à amigos
     *
     * @constant
     */
    const SO_AMIGOS = 1;

    /**
     * Indicador de Mensagens Pessoais Desabilitadas
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
        switch($codigo) {
            case self::LIVRE:
                return 'Mensagens Pessoais Livres';
            case self::SO_AMIGOS;
                return 'Mensagens Pessoais restritas somente à amigos';
            case self::DESABILITADO:
                return 'Mensagens Pessoais Desabilitadas';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
