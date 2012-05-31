<?php
if(count($listaRandonicaAmigos)>0){
foreach ($listaRandonicaAmigos as $row) {
    echo $row->toHTML('imagem_pequena');
}
}