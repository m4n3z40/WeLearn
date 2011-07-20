<?php
/**
 * Created by Allan Marques
 * Date: 20/07/11
 * Time: 13:24
 * 
 * Description:
 *
 */

/**
 * @throws WeLearn_Base_CodigoEnumIncorretoException
 */
abstract class WeLearn_Usuarios_PrivacidadeNotificacoes implements WeLearn_Base_IEnum {

    /**
     * Indicador da Privacidade de Notificações Habilitadas
     *
     * @constant
     */
    const HABILITADO = 0;

    /**
     * Indicador da Privacidade de Notificações Desabilitadas
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
    public static function getDescricao( $codigo )
    {
        switch( $codigo ) {
            case self::HABILITADO:
                return 'Notificações Habilitadas';
            case self::DESABILITADO:
                return 'Notificações Desabilitadas';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
