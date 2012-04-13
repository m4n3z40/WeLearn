<?php foreach ($listaRecursos as $recurso): ?>
<tr>
    <td>
        <div>
            <h4><?php echo $recurso->nome ?></h4>
            <p><?php echo $recurso->descricao ?></p>
        </div>
    </td>
    <td>
        <dl>
            <dt>Por:</dt>
            <dd><?php echo anchor('/usuario/' . $recurso->criador->id,
                                  $recurso->criador->nome) ?></dd>
            <dt>Em:</dt>
            <dd><?php echo date('d/m/Y H:i:s', $recurso->dataInclusao) ?></dd>
        </dl>
    </td>
    <td>
        <?php echo $recurso ?>
    </td>
    <td><?php echo anchor('/curso/conteudo/recurso/alterar/' . $recurso->id, 'Alterar', 'class="a-alterar-recurso"') ?></td>
    <td><?php echo anchor('/curso/conteudo/recurso/remover/' . $recurso->id, 'Remover', 'class="a-remover-recurso"') ?></td>
</tr>
<?php endforeach; ?>