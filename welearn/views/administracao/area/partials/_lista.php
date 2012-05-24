<?php foreach ($listaArea as $area): ?>
<tr>
    <td><?php echo $area->nome ?></td>
    <td><?php echo $area->descricao ?></td>
    <td><?php echo anchor('/administracao/area/alterar/' . $area->id, 'Alterar', 'class="a-alterar-categoria-area"') ?></td>
    <td><?php echo anchor('/administracao/area/remover/' . $area->id, 'Remover', 'class="a-remover-categoria-area"') ?></td>
</tr>
<?php endforeach ?>