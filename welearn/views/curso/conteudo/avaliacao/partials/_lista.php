<?php foreach ($listaQuestoes as $questao): ?>
<tr>
    <td><?php echo ( strlen($questao->enunciado) > 155 )
                    ? '"' . substr($questao->enunciado, 0, 155) . '..."'
                    : '"' . $questao->enunciado . '"' ?></td>
    <td>
        <nav class="questao-adminpanel">
            <ul>
                <li>
                    <?php echo anchor(
                        '/conteudo/avaliacao/exibir_questao/' . $questao->id,
                        'Visualizar',
                        'class="a-exibir-questao"'
                    ) ?>
                </li>
                <li>
                    <?php echo anchor(
                        '/conteudo/avaliacao/alterar_questao/' . $questao->id,
                        'Alterar',
                        'class="a-alterar-questao"'
                    ) ?>
                </li>
                <li>
                    <?php echo anchor(
                        '/conteudo/avaliacao/remover_questao/' . $questao->id,
                        'Remover',
                        'class="a-remover-questao"'
                    ) ?>
                </li>
            </ul>
        </nav>
    </td>
</tr>
<?php endforeach; ?>