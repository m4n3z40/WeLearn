<?php
/**
 * Created by Thiago Monteiro
 * Date: 26/07/11
 * Time: 18:25
 *
 * Description:
 *
 */
abstract class WeLearn_Compartilhamento_TipoFeed implements WeLearn_Base_IEnum
{

    /**
     * Compartilhamento referente ao Status do usuario
     * @constant
     */
    const STATUS = 0;

    /**
     * compartilhamento de um link
     *
     * @constant
     */
    const LINK = 1;

    /**
     * Compartilhamento de uma imagem
     * @constant
     */
    const IMAGEM = 2;

    /**
     * Compartilhamento de um video
     * @constant
     */
    const VIDEO = 3;

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
            case self::STATUS:
                return 'Compartilhamento de Status';
            case self::LINK:
                return 'Compartilhamento de Link';
            case self::IMAGEM:
                return 'Compartilhamento de Imagem';
            case self::VIDEO:
                return 'Compartilhamento de Video';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}