<?if(count($listaRandonicaCursosInscritos)>0):?>
<div class="widget">
    <h3><?=$legenda?></h3>
    <?echo anchor($link,'cursos inscrito');?>
    <ul>
        <?foreach ($listaRandonicaCursosInscritos as $row):?>
        <li><?=$row->toHTML('imagem_mini')?></li>
        <?endforeach;?>
    </ul>
</div>
<?endif;?>