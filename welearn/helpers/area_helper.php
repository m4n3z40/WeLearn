<?php

function lista_areas_para_dados_dropdown(array $areas = null)
{
    if ( is_null($areas) ) {
        $areaDao = WeLearn_DAO_DAOFactory::create('AreaDAO');
        $areas = $areaDao->recuperarTodos();
    }

    $dropdownAreas = array();
    
    $dropdownAreas['0'] = 'Selecione uma área de segmento';

    foreach ($areas as $area) {
        $dropdownAreas[$area->getId()] = $area->getDescricao();
    }
    
    return $dropdownAreas;
}