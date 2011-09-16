<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 17:23
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_StatusCurso implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const CONTEUDO_BLOQUEADO = 0;

    /**
     * @constant
     */
    const CONTEUDO_ABERTO = 1;

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
            case self::CONTEUDO_BLOQUEADO:
                return 'Conteúdo do Curso Bloqueado';
            case self::CONTEUDO_ABERTO:
                return 'Conteúdo do Curso Aberto';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}