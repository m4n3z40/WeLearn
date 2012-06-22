<?php foreach ($listaEnquetes as $enquete): ?>
<tr>
    <td>
        <h4>
        <?php
            echo anchor(
                     'curso/enquete/exibir/' . $enquete->id,
                     strlen($enquete->questao) > 80 ? substr($enquete->questao, 0, 80) . '...' : $enquete->questao
                 );
        ?>
        </h4>
        <ul>
            <li>Criada por: <?php echo $enquete->criador->toHTML('somente_link') ?></li>
            <li>Criada em: <?php echo date('d/m/Y H:i:s', $enquete->dataCriacao) ?></li>
            <li>Fecha as votações em: <?php echo ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ?
                                                 date('d/m/Y H:i:s', $enquete->dataExpiracao) :
                                                 WeLearn_Cursos_Enquetes_SituacaoEnquete::getDescricao($enquete->situacao) ?></li>
            <li>Status: <?php echo WeLearn_Cursos_Enquetes_StatusEnquete::getDescricao($enquete->status) ?></li>
            <li>Total de participações: <?php echo $enquete->totalVotos ?></li>
        </ul>
    </td>
    <td>
        <?php echo gerar_menu_autorizado(
            array(
                array(
                    'uri' => 'curso/enquete/alterar/' . $enquete->id,
                    'texto' => 'Alterar',
                    'autor' => $enquete->criador
                ),
                array(
                    'uri' => 'curso/enquete/remover/' . $enquete->id,
                    'texto' => 'Remover',
                    'attr' => 'class="a-enquete-remover"',
                    'acao' => 'enquete/remover',
                    'papel' => $papelUsuarioAtual,
                    'autor' => $enquete->criador
                ),
                array(
                    'uri' => 'curso/enquete/alterar_status/' . $enquete->id,
                    'texto' => ($enquete->status == WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA) ? 'Desativar' : 'Ativar',
                    'attr' => 'class="a-enquete-alterarstatus"',
                    'acao' => 'enquete/alterar_status',
                    'papel' => $papelUsuarioAtual
                ),
                array(
                    'uri' => 'curso/enquete/alterar_situacao/' . $enquete->id,
                    'texto' => ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ? 'Fechar' : 'Reabrir',
                    'attr' => 'class="a-enquete-alterarsituacao"',
                    'acao' => 'enquete/remover',
                    'papel' => $papelUsuarioAtual
                )
            ),
            array('<li>','</li>'),
            array('<nav class="enquete-adminpanel"><ul>','</ul></nav>')
        ) ?>
    </td>
</tr>
<?php endforeach; ?>