<?php
/**
 * Created by Allan Marques
 * Date: 21/07/11
 * Time: 17:47
 *
 * Description:
 *
 */

abstract class WeLearn_Usuarios_Autorizacao_NivelAcesso implements WeLearn_Base_IEnum
{
    /**
     * @constant
     */
    const USUARIO = 0;

    /**
     * @constant
     */
    const ALUNO = 1;

    /**
     * @constant
     */
    const ALUNO_INSCRICAO_PENDENTE = 11;

    /**
     * @constant
     */
    const GERENCIADOR_AUXILIAR = 2;

    /**
     * @constant
     */
    const GERENCIADOR_CONVITE_PENDENTE = 22;

    /**
     * @constant
     */
    const GERENCIADOR_PRINCIPAL = 3;

    /**
     * @constant
     */
    const ADMINISTRADOR_DO_SERVICO = 999;

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
            case self::USUARIO:
                return 'Usuário';
            case self::ALUNO:
                return 'Aluno';
            case self::ALUNO_INSCRICAO_PENDENTE:
                return 'Inscrição Pendente';
            case self::GERENCIADOR_AUXILIAR:
                return 'Gerenciador Auxiliar';
            case self::GERENCIADOR_CONVITE_PENDENTE:
                return 'Convite Pendente';
            case self::GERENCIADOR_PRINCIPAL:
                return 'Gerenciador Principal';
            case self::ADMINISTRADOR_DO_SERVICO:
                return 'Administrador do Serviço';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}
