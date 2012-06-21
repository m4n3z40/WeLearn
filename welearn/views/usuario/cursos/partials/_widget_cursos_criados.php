<?if(count($listaRandonicaCursosCriados)>0):?>
<div class="widget">
    <h3><?=$legenda?></h3>
    <ul>
        <?foreach ($listaRandonicaCursosCriados as $row):?>
        <li><?echo $row->htmlImagemLink(true);?></li>
        <?endforeach;?>
    </ul>
    <?echo anchor($link,'Mais...');?>
</div>
<?endif;?>