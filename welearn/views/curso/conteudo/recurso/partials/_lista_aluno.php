<?php foreach ($listaRecursos as $recurso): ?>
<li>
    <dl>
        <dt>Nome:</dt>
        <dd><?php echo $recurso->nome ?></dd>
        <dt>Descrição:</dt>
        <dd><?php echo nl2br($recurso->descricao) ?></dd>
    </dl>
    <?php echo $recurso ?>
</li>
<?php endforeach; ?>