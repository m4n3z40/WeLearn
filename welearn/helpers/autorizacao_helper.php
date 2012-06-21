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