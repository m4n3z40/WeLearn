<?php
/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 01/06/12
 * Time: 20:11
 * To change this template use File | Settings | File Templates.
 */
class WeLearn_Cursos_Conteudo_TipoConteudo implements  WeLearn_Base_IEnum
{
    /**
     * @constant
     */
    const NENHUM = 'nenhum';

    /**
     * @constant
     */
    const PAGINA = 'pagina';

    /**
     * @constant
     */
    const AVALIACAO = 'avaliacao';

    /**
     * Retorna a descrição do Enum passado por parametro.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     */
    public static function getDescricao($codigo)
    {
        switch ($codigo) {
            case self::NENHUM:
                return 'Nenhum';
            case self::PAGINA:
                return 'Página';
            case self::AVALIACAO:
                return 'Avaliação';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
