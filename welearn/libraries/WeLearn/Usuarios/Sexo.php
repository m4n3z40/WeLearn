<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 24/08/11
 * Time: 12:35
 * To change this template use File | Settings | File Templates.
 */
 
class WeLearn_Usuarios_Sexo implements WeLearn_Base_IEnum {

    const MASCULINO = 'M';

    const FEMININO = 'F';

    const NAO_EXIBIR = 'NE';

    /**
     * Retorna a descrição do Enum passado por parametro.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao($codigo)
    {
        switch($codigo) {
            case self::MASCULINO:
                return 'Masculino';
            case self::FEMININO:
                return 'Feminino';
            case self::NAO_EXIBIR:
                return 'Não exibir';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
