<?php

$curl = curl_init('http://localhost:8080');

curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $curl, CURLOPT_POST, true );

$dados = array(
    'dadosJson' => json_encode(array(
        array(
            'usuario' => 'm4n3z40',
            'tipo' => 'notificacao',
            'msg' => '<div>Isso é uma notificação de teste!<br>Aqui virá as notificações de usuário!</div><br>(Clique nesta notificação para fechá-la)',
            'url' => 'http://welearn.com'
        ),
        array(
            'usuario' => 'victor',
            'tipo' => 'notificacao',
            'msg' => 'testetestetsteste1',
            'url' => 'http://welearn.com/1'
        ),
        array(
            'usuario' => 'thiago',
            'tipo' => 'notificacao',
            'msg' => 'testetestetsteste2',
            'url' => 'http://welearn.com/2'
        )
    ))
);

curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($dados) );

$resultado = curl_exec( $curl );

curl_close( $curl );

echo $resultado;