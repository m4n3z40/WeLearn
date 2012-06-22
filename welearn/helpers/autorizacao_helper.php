<?php

function is_autorizado ( WeLearn_Usuarios_Autorizacao_Papel $usuario, $acao = '' )
{
    $ci =& get_instance();

    if ( $acao ) {

        return $ci->autorizacao->isAutorizado( $usuario, $acao );

    } else {

        return $ci->autorizacao->isAutorizadoNaAcaoAtual( $usuario );

    }
}

function is_autor ( WeLearn_Usuarios_Usuario $autorDoConteudo )
{
    $ci =& get_instance();

    return $ci->autorizacao->isAutorUsuarioAtual( $autorDoConteudo );
}

function gerar_menu_autorizado (
    array $dados,
    $itemWrapper = array('',''),
    $containerWrapper = array('','')
)
{
    if ( empty( $dados ) ) {
        return '';
    }

    $menuStr = '';

    for ($i = 0; $i < count($dados); $i++) {

        if ( isset( $dados[$i]['papel'], $dados[$i]['autor'] ) ) {

            if (
                is_autorizado(
                    $dados[$i]['papel'],
                    isset( $dados[$i]['acao'] ) ? $dados[$i]['acao'] : ''
                )
                || is_autor( $dados[$i]['autor'] )
            ) {

                $menuStr .= _gerar_item_hiperlink( $dados[$i], $itemWrapper );

            }

        } elseif ( isset( $dados[$i]['papel'] ) ) {

            if (
                is_autorizado(
                    $dados[$i]['papel'],
                    isset( $dados[$i]['acao'] ) ? $dados[$i]['acao'] : ''
                )
            ) {

                $menuStr .= _gerar_item_hiperlink( $dados[$i], $itemWrapper );

            }

        } elseif ( isset( $dados[$i]['autor'] ) ) {

            if ( is_autor( $dados[$i]['auto'] ) ) {

                $menuStr .= _gerar_item_hiperlink( $dados[$i], $itemWrapper );

            }

        } else {

            $menuStr .= _gerar_item_hiperlink( $dados[$i], $itemWrapper );

        }

    }

    if ($menuStr != '') {

        $menuStr = $containerWrapper[0] . $menuStr . $containerWrapper[1];

    }

    return $menuStr;
}

function _gerar_item_hiperlink(array $dados, $itemWrapper = array('','')) {

    return $itemWrapper[0] . anchor(
        isset( $dados['uri'] ) ? $dados['uri'] : '',
        isset( $dados['texto'] ) ? $dados['texto'] : '',
        isset( $dados['attr'] ) ? $dados['attr'] : ''
    ) . $itemWrapper[1];

}