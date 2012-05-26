<head>
        <h1>Aqui estão Listadas Todas as Areas</h1>
        <p><h4>Se voce deseja criar uma Nova Area    <?php echo anchor('/administracao/area/criar/' , 'É por aqui!') ?></h4></p>

</head>


<table>
    <tr>
        <th>Nome da Area</th>
    </tr>

           <?php echo $listaAreas; ?>
</table>
