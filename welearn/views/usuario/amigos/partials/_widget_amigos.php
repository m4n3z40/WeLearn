
<?if(count($listaRandonicaAmigos)>0):?>
<div class="widget">
    <h3><?=$legenda?></h3>
    <ul class="ul-grid-cursos-alunos">
    <?foreach ($listaRandonicaAmigos as $row):?>
        <li><?=$row->toHTML('imagem_mini')?></li>
    <?endforeach;?>
    </ul>
    <?echo anchor($link,'Mais...');?>
</div>
<?endif;?>