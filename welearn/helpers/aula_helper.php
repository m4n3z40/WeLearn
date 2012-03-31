<?php

function lista_aulas_para_dados_dropdown(array $aulas)
{
    $dropdownAulas = array( '0' => 'Selecione uma aula...' );

    foreach ($aulas as $aula) {
        $dropdownAulas[ $aula->getId() ] = $aula->getNome();
    }

    return $dropdownAulas;
}