<?php

function lista_modulos_para_dados_dropdown(array $modulos)
{
    $dropdownModulos = array('0' => 'Selecione um módulo de curso...');

    $i = 0;
    foreach ($modulos as $modulo) {
        $dropdownModulos[ $modulo->getId() ] = 'Módulo ' . ++$i . ': ' . $modulo->getNome();
    }

    return $dropdownModulos;
}