<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 16:11
 * 
 * Description:
 *
 */
 
abstract class WeLearn_Cursos_Recursos_TipoRecurso implements WeLearn_Base_IEnum
{
    const GERAL = 0;
    const RESTRITO = 1;

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
            case 0:
                return 'Recurso Geral'; break;
            case 1:
                return 'Recurso Restrito'; break;
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
