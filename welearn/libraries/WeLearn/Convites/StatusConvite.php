<?php
/**
 * Created by Thiago Monteiro
 * Date: 24/07/11
 * Time: 11:42
 *
 * Description:
 *
 */

abstract class WeLearn_Convites_StatusConvite implements WeLearn_Base_IEnum
{
    /*
     * indicador do status do convite em espera novo
     */
    const EM_ESPERA_NOVO = 0;

    /*
     * indicador do status do convite em espera, mas ja visualizado
     */
    const EM_ESPERA_VISTO = 1;

    /*
     * indicador do status do convite como aceito
     */
    const ACEITO = 2;

    /*
     * indicador do status do convite como recusado
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
        switch ($codigo)
        {
            case self::EM_ESPERA_NOVO:
                return "Convite Em Espera de Aprovação";
            case self::EM_ESPERA_VISTO:
                return "Convite Aguardando Visualização";
            case self::ACEITO:
                return "Convite Aceito";
            case self::RECUSADO:
                return "Convite Recusado";
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}