<?php foreach ($listaUsuarios as $usuario): ?>
<li data-id-usuario="<?php echo $usuario->id ?>">
    <div>
        <?php echo $usuario->toHTML('imagem_pequena') ?>
    </div>
    <ul>
        <li><a href="#" class="a-adicionar-convite-usuario">Adicionar à lista de convites</a></li>
    </ul>
</li>
<?php endforeach; ?>