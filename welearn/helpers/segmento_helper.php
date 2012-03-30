<?php

function lista_segmentos_para_dados_dropdown(array $segmentos = null)
{
    $dropdownSegmentos = array( '0' => 'Selecione um segmento nesta área...' );

    if( ! is_null($segmentos) ) {
        foreach ($segmentos as $segmento) {
            $dropdownSegmentos[$segmento->getId()] = $segmento->getDescricao();
        }
    }
    
    return $dropdownSegmentos;
}