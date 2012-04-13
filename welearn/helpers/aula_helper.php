<?php

function lista_aulas_para_dados_dropdown(array $aulas)
{
    $dropdownAulas = array( '0' => 'Selecione uma aula...' );

    $i = 0;
    foreach ($aulas as $aula) {
        $dropdownAulas[ $aula->getId() ] = 'Aula ' . ++$i . ': ' . $aula->getNome();
    }

    return $dropdownAulas;
}