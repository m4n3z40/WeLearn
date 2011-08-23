<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 17:47
 *
 * Description:
 *
 */

abstract class WeLearn_Usuarios_NivelAcesso implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const ALUNO = 0;

    /**
     * @constant
     */
    const MODERADOR = 1;

    /**
     * @constant
     */
    const INSTRUTOR = 2;

    /**
     * @constant
     */
    const GERENCIADOR_AUXILIAR = 3;

    /**
     * @constant
     */
    const GERENCIADOR_PRINCIPAL = 4;

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
            case self::ALUNO:
                return 'Aluno';
            case self::MODERADOR:
                return 'Moderador';
            case self::INSTRUTOR:
                return 'Instrutor';
            case self::GERENCIADOR_AUXILIAR:
                return 'Gerenciador Auxiliar';
            case self::GERENCIADOR_PRINCIPAL:
                return 'Gerenciador Principal';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
