<ul>
    <?php foreach ($listaResultados as $row):?>
    <li><?= $row->toHtml('imagem_pequena');?></li>
    <?php endforeach;?>
</ul>
