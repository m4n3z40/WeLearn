<?php

function lista_areas_para_dados_dropdown(array $areas)
{
    $dropdownAreas = array();
    
    $dropdownAreas[''] = 'Selecione uma área de segmento';
    
    foreach ($areas as $area) {
        $dropdownAreas[$area->getId()] = $area->getDescricao();
    }
    
    return $dropdownAreas;
}