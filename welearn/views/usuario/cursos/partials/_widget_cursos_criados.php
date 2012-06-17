<?if(count($listaRandonicaCursosCriados)>0):?>
<div class="widget">
    <h3><?=$legenda?></h3>
    <?echo anchor($link,'cursos gerenciados');?>
    <ul>
        <?foreach ($listaRandonicaCursosCriados as $row):?>
        <li><?=$row->toHTML('imagem_mini')?></li>
        <?endforeach;?>
    </ul>
</div>
<?endif;?>