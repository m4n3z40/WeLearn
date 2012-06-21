<?php foreach ($listaCategorias as $categoria): ?>
<tr>
    <td width="20%"><?php echo $categoria->nome ?></td>
    <td width="35%"><?php echo $categoria->descricao ?></td>
    <td><?php echo date('d/m/Y H:i:s', $categoria->dataCriacao) ?></td>
    <?php if ($categoria->criador): ?>
        <td><?php echo $categoria->criador->toHTML('somente_link') ?></td>
    <?php else: ?>
        <td>O usuário criador deste curso não está mais no WeLearn :(</td>
    <?php endif; ?>
    <td>
        <nav class="enquete-admin-panel">
            <ul>
                <li><?php echo anchor('/curso/forum/categoria/alterar/' . $categoria->id, 'Alterar', 'class="a-alterar-categoria-forum"') ?></li>
                <li><?php echo anchor('/curso/forum/categoria/remover/' . $categoria->id, 'Remover', 'class="a-remover-categoria-forum"') ?></li>
            </ul>
        </nav>
    </td>
</tr>
<?php endforeach ?>