<?php

function lista_segmentos_para_dados_dropdown(array $segmentos)
{
    $dropdownSegmentos = array();
    
    $dropdownSegmentos[''] = 'Selecione um segmento nesta área...';
    
    foreach ($segmentos as $segmento) {
        $dropdownSegmentos[$segmento->getId()] = $segmento->getDescricao();
    }
    
    return $dropdownSegmentos;
}