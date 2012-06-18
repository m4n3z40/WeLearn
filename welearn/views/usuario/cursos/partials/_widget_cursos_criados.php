<?if(count($listaRandonicaCursosCriados)>0):?>
<div class="widget">
    <h3><?=$legenda?></h3>
    <?echo anchor($link,'cursos gerenciados');?>
    <ul>
        <?foreach ($listaRandonicaCursosCriados as $row):?>
        <li><?echo $row->htmlImagemLink(true);?></li>
        <?endforeach;?>
    </ul>
</div>
<?endif;?>