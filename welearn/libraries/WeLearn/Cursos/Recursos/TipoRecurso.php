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
    //@TODO: Definir tipos permitidos dos recursos extras

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao($codigo)
    {
        // TODO: Implement getDescricao() method.
    }
}
