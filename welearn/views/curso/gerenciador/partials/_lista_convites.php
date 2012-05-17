<?php foreach ($listaConvites as $usuario): ?>
<li data-id-usuario="<?php echo $usuario->id ?>">
    <div>
        <?php echo $usuario->toHTML('imagem_pequena') ?>
    </div>
    <ul>
        <li><?php echo anchor(
                '/curso/gerenciador/cancelar_convite/' . $idCurso,
                'Cancelar Convite',
                'class="a-cancelar-convite"'
        ) ?></li>
    </ul>
</li>
<?php endforeach; ?>