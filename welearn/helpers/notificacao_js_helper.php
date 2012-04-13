<?php

function create_notificacao_array ($nivel = 'sucesso', $msg = '', $tempo = 5000,
                                   $fechavel = true, $redirecionarAoFechar = false,
                                   $redirecionarParaUrl = '')
{
    switch ($nivel) {
        case 'sucesso': $nivel = 'success'; break;
        case 'erro': $nivel = 'error'; break;
        case 'aviso':
        default: $nivel = 'alert';
    }

    $notificacao = array(
        'nivel' => $nivel,
        'msg' => $msg,
        'tempo' => $tempo
    );

    if ( ! $fechavel ) {
        $notificacao['fechavel'] = false;
    }

    if ( $redirecionarAoFechar ) {
        $notificacao['redirecionarAoFechar'] = true;
        $notificacao['redirecionarParaUrl'] = $redirecionarParaUrl;
    }

    return $notificacao;
}

function create_notificacao_json ($nivel = 'sucesso', $msg = '', $tempo = 5000,
                                   $fechavel = true, $redirecionarAoFechar = false,
                                   $redirecionarParaUrl = '')
{
    return Zend_Json::encode(
        create_notificacao_array(
            $nivel, $msg, $tempo, $fechavel, $redirecionarAoFechar, $redirecionarParaUrl
        )
    );
}