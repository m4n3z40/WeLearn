<?if(count($listaRandonicaCursosInscritos)>0):?>
<div class="widget">
    <h3><?=$legenda?></h3>
    <ul>
        <?foreach ($listaRandonicaCursosInscritos as $row):?>
        <li><?echo $row->htmlImagemLink(true);?></li>
        <?endforeach;?>
    </ul>
    <?echo anchor($link,'Mais...');?>
</div>
<?endif;?>