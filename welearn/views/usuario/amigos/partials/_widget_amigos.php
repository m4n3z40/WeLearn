
<?if(count($listaRandonicaAmigos)>0):?>
<div class="widget">
    <h3>Amigos</h3>
    <ul>
    <?foreach ($listaRandonicaAmigos as $row):?>
        <li><?=$row->toHTML('imagem_mini')?></li>
    <?endforeach;?>
    </ul>
</div>
<?endif;?>