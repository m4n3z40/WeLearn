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
    <?php echo gerar_menu_autorizado(
        array(
            array(
                'uri' => '/curso/forum/categoria/alterar/' . $categoria->id,
                'texto' => 'Alterar',
                'attr' => 'class="a-alterar-categoria-forum"',
                'acao' => 'categoria/alterar',
                'papel' => $papelUsuarioAtual
            ),
            array(
                'uri' => '/curso/forum/categoria/remover/' . $categoria->id,
                'texto' => 'Remover',
                'attr' => 'class="a-remover-categoria-forum"',
                'acao' => 'categoria/remover',
                'papel' => $papelUsuarioAtual
            )
        ),
        array('<li>','</li>'),
        array('<nav class="enquete-admin-panel"><ul>','</ul></nav>')
    ) ?>
    </td>
</tr>
<?php endforeach ?>