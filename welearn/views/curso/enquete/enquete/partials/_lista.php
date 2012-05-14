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
        <nav class="enquete-adminpanel">
            <ul>
                <li><?php echo anchor('curso/enquete/alterar/' . $enquete->id, 'Alterar') ?></li>
                <li><?php echo anchor('curso/enquete/remover/' . $enquete->id, 'Remover',
                    array('class' => 'a-enquete-remover')) ?></li>
                <li><?php echo anchor('curso/enquete/alterar_status/' . $enquete->id,
                    ($enquete->status == WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA) ? 'Desativar' : 'Ativar',
                    array('class' => 'a-enquete-alterarstatus')) ?></li>
                <li><?php echo anchor('curso/enquete/alterar_situacao/' . $enquete->id,
                    ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ? 'Fechar' : 'Reabrir',
                    array('class' => 'a-enquete-alterarsituacao')) ?></li>
            </ul>
        </nav>
    </td>
</tr>
<?php endforeach; ?>