<?php foreach ($listaGerenciadores as $gerenciador): ?>
<li data-id-gerenciador="<?php echo $gerenciador->id ?>">
    <div>
        <?php echo $gerenciador ->toHTML('imagem_pequena') ?>
    </div>
    <ul>
        <li><?php echo anchor(
                '/curso/gerenciador/desvincular/' . $idCurso,
                'Revogar Cargo de Gerenciador',
                'class="a-desvincular-gerenciador"'
        ) ?></li>
    </ul>
</li>
<?php endforeach; ?>