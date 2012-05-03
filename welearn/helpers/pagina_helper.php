<?php

function lista_paginas_para_dados_dropdown(array $paginas)
{
    $dropdownPaginas = array( '0' => 'Selecione uma página...' );

    $i = 0;
    foreach ($paginas as $pagina) {
        $dropdownPaginas[ $pagina->getId() ] = 'Página ' . ++$i . ': ' . $pagina->getNome();
    }

    return $dropdownPaginas;
}