<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 17:23
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_StatusSugestaoCurso implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const EM_ESPERA = 0;

    /**
     * @constant
     */
    const GEROU_CURSO = 1;

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
            case self::EM_ESPERA:
                return 'Sugestão em Espera';
            case self::GEROU_CURSO:
                return 'Curso foi criado a partir desta sugestão';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}