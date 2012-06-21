<?php foreach ($listaRecursos as $recurso): ?>
<tr>
    <td>
        <div>
            <h4><?php echo $recurso->nome ?></h4>
            <p><?php echo nl2br($recurso->descricao) ?></p>
        </div>
    </td>
    <td>
        <ul>
            <li>Por: <span><?php echo $recurso->criador->toHTML('somente_link') ?></span></li>
            <li>Em: <span><?php echo date('d/m/Y H:i:s', $recurso->dataInclusao) ?></span></li>
        </ul>
    </td>
    <td>
        <?php echo $recurso ?>
    </td>
    <td>
        <nav class="recurso-adminpanel">
            <ul>
                <li><?php echo anchor('/curso/conteudo/recurso/alterar/' . $recurso->id, 'Alterar', 'class="a-alterar-recurso"') ?></li>
                <li><?php echo anchor('/curso/conteudo/recurso/remover/' . $recurso->id, 'Remover', 'class="a-remover-recurso"') ?></li>
            </ul>
        </nav>
    </td>
</tr>
<?php endforeach; ?>