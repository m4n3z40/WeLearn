<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 14:20
 *
 * Description:
 *
 */

/**
 * @throws WeLearn_Base_CodigoEnumIncorretoException
 */
abstract class WeLearn_Usuarios_StatusAmizade implements WeLearn_Base_IEnum
{

    /**
     * Indicador do Status da Amizade com Requisição em Espera
     *
     * @constant
     */
    const REQUISICAO_EM_ESPERA = 0;

    /**
     * Indicador do Status da Amizade Ativa
     *
     * @constant
     */
    const AMIGOS = 1;

    /**
     * Indicador do Status da Amizade Ativa
     *
     * @constant
     */
     const NAO_AMIGOS = -1;

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
            case self::REQUISICAO_EM_ESPERA:
                return 'Requisição de Amizade em Espera de Aprovação';
            case self::AMIGOS:
                return 'Amizade Ativa';
            case self::NAO_AMIGOS:
                return 'Sem Amizade';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
