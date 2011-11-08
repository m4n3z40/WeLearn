<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 17:38
 *
 * Description:
 *
 */

abstract class WeLearn_Cursos_SituacaoParticipacaoCurso implements WeLearn_Base_IEnum
{

    /**
     * @constant
     */
    const INSCRICAO_EM_ESPERA = 0;

    /**
     * @constant
     */
    const PARTICIPACAO_ATIVA = 1;

    /**
     * @constant
     */
    const CURSO_CONCLUIDO = 2;

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
            case self::INSCRICAO_EM_ESPERA:
                return 'Inscrição em Espera';
            case self::PARTICIPACAO_ATIVA:
                return 'Participação Ativa no Curso';
            case self::CURSO_CONCLUIDO:
                return 'Curso Concluído';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
