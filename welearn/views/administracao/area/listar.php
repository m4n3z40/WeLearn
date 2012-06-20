<head>
        <h1>Aqui estão Listadas Todas as Areas</h1>
        <p><h4>Se voce deseja criar uma Nova Area    <?php echo anchor('/administracao/area/criar/' , 'É por aqui!') ?></h4></p>

</head>

<h3>Nome da Area</h3>


<ul id = 'area-lista-segmento'>
           <?php echo $listaAreas;?>
</ul>
<?php echo form_open($formAction,$formExtra) ?>
    <?php echo $criarSegmento;?>
<?php echo form_close();?>
