<?php

abstract class WeLearn_Notificacoes_StatusNotificacao implements WeLearn_Base_IEnum
{
    const NOVO = 0;
    const LIDO = 1;

    public static function getDescricao($codigo)
    {
        switch ($codigo) {
            case self::NOVO:
                return 'Notificação Nova';
            case self::LIDO:
                return 'Notificação Lida';
            default:
                throw new WeLearn_Base_CodigoEnumIncorretoException();
        }
    }
}