<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 00:11
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_Conteudo_StatusConteudo implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const BLOQUEADO = 0;

    /**
     * @constant
     */
    const ACESSANDO = 1;

    /**
     * @constant
     */
    const ACESSADO = 2;

    /**
     * @constant
     */
    const FINALIZADO = 3;

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
            case self::BLOQUEADO:
                return 'Bloqueado';
            case self::ACESSANDO:
                return 'Acessando';
            case self::ACESSADO:
                return 'Acessado';
            case self::FINALIZADO:
                return 'Finalizado';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
