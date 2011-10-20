<?php foreach ($listaCategorias as $categoria): ?>
<tr>
    <td><?php echo $categoria->nome ?></td>
    <td><?php echo $categoria->descricao ?></td>
    <td><?php echo date('d/m/Y H:i:s', $categoria->dataCriacao) ?></td>
    <?php if ($categoria->criador): ?>
        <td><?php echo anchor('usuario/' . $categoria->criador->id, $categoria->criador->nome) ?></td>
    <?php else: ?>
        <td>O usuário criador deste curso não está mais no WeLearn :(</td>
    <?php endif; ?>
    <td><?php echo anchor('/forum/categoria/alterar/' . $categoria->id, 'Alterar', 'class="a-alterar-categoria-forum"') ?></td>
    <td><?php echo anchor('/forum/categoria/remover/' . $categoria->id, 'Remover', 'class="a-remover-categoria-forum"') ?></td>
</tr>
<?php endforeach ?>
