<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 03:43
 * 
 * Description:
 *
 */

/**
 *
 */
interface WeLearn_Base_IEnum {

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @abstract
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao( $codigo );
}
