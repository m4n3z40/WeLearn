<?php

function lista_modulos_para_dados_dropdown(array $modulos)
{
    $dropdownModulos = array('0' => 'Selecione um módulo de curso...');

    foreach ($modulos as $modulo) {
        $dropdownModulos[ $modulo->getId() ] = $modulo->getNome();
    }

    return $dropdownModulos;
}