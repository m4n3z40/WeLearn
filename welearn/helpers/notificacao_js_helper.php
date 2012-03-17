<?php

function create_notificacao_array ($nivel = 'sucesso', $msg = '', $tempo = 10000,
                                   $fechavel = true, $textoFechar = '', $redirecionarAoFechar = false,
                                   $redirecionarParaUrl = '')
{
    $notificacao = array(
        'nivel' => $nivel,
        'msg' => $msg,
        'tempo' => $tempo
    );

    if ( ! $fechavel ) {
        $notificacao['fechavel'] = false;
    }

    if ( $textoFechar ) {
        $notificacao['textoFechar'] = $textoFechar;
    }

    if ( $redirecionarAoFechar ) {
        $notificacao['redirecionarAoFechar'] = true;
        $notificacao['redirecionarParaUrl'] = $redirecionarParaUrl;
    }

    return $notificacao;
}

function create_notificacao_json ($nivel = 'sucesso', $msg = '', $tempo = 10000,
                                   $fechavel = true, $textoFechar = '', $redirecionarAoFechar = false,
                                   $redirecionarParaUrl = '')
{
    return Zend_Json::encode(
        create_notificacao_array(
            $nivel, $msg, $tempo, $fechavel, $textoFechar, $redirecionarAoFechar, $redirecionarParaUrl
        )
    );
}