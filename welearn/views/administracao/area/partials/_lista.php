<?php foreach ($listaAreas as $area): ?>

    <li>
     <a href= 'segmento/recuperar_lista/<?php echo $area->id?>' data-id = "<?php echo $area->id?>"> <?php echo $area->descricao ?></a>
    </li>

<?php endforeach ?>