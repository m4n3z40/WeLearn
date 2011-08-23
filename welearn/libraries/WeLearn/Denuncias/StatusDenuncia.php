<?php

/**
 * Created by Thiago Monteiro
 * Date: 22/07/11
 * Time: 19:37
 *
 * Description:
 *
 */

class WeLearn_Denuncias_StatusDenuncia implements WeLearn_Base_IEnum
{
    /**
     * Indicador de Denuncia em espera ainda não visualizado pelo usuario destinatario
     * @constant
     */
    const EM_ESPERA_NOVO = 0;

    /**
     * Indicador de Denuncia já visualizado pelo usuario destinatario, e em espera
     * @constant
     */
    const EM_ESPERA_VISTO = 1;

    /**
     * Indicador de Denuncia Aceita, denuncia foi aceita e agora aguarda uma solução
     * @constant
     */
    const ACEITO = 2;

    /**
     * Indicador de Denuncia Recusada, denuncia recusada pelo usuario destinatario
     * @constant
     */
    const RECUSADO = 3;

    /**
     * Indicador de Denuncia solucionada, após gerar uma SolucaoDenuncia,
     * o status da denuncia é alterado para SOLUCIONADO
     * @constant
     */
    const SOLUCIONADO = 4;

    /**
     * Retorna a descrição do Enum passado por parametru.
     *
     * @param $codigo int Codigo da descrição do Enum a ser retornada.
     * @return string
     * @throws WeLearn_Base_CodigoEnumIncorretoException
     * @static
     */
    public function getDescricao($codigo)
    {
        switch ($codigo) {
            case self::EM_ESPERA_NOVO:
                return 'Denuncia em espera';
            case self::EM_ESPERA_VISTO:
                return 'Denuncia visualizada em espera';
            case self::ACEITO:
                return 'Denuncia aceita, aguardando solução';
            case self::RECUSADO:
                return 'Denuncia recusada';
            case self::SOLUCIONADO:
                return 'Denuncia solucionada';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }

}