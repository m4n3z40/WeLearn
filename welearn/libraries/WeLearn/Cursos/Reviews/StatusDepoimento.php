<?php
/**
 * Created by Allan Marques
 * Date: 22/07/11
 * Time: 16:21
 * 
 * Description:
 *
 */
 
abstract class WeLearn_Cursos_Reviews_StatusDepoimento implements  WeLearn_Base_IEnum
{
    /**
     * @constant
     */
    const EM_ESPERA_NOVO = 0;

    /**
     * @constant
     */
    const EM_ESPERA_VISTO = 1;

    /**
     * @constant
     */
    const ACEITO = 2;

    /**
     * @constant
     */
    const RECUSADO = 3;

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao($codigo)
    {
        switch($codigo) {
            case self::EM_ESPERA_NOVO:
                return 'Depoimento novo em espera';
            case self::EM_ESPERA_VISTO:
                return 'Depoimento já visto em espera';
            case self::ACEITO:
                return 'Depoimento aceito';
            case self::RECUSADO:
                return 'Depoimento Recusado';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
