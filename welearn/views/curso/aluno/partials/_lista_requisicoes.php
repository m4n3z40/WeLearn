<?php foreach ($listaRequisicoes as $requisitante): ?>
<li>
    <div>
        <?php echo $requisitante->toHTML('imagem_pequena') ?>
        <ul>
            <li><?php echo anchor(
                '/curso/aluno/aceitar_requisicao/' . $idCurso,
                'Permitir',
                'class="a-aceitar-requisicao-inscricao" data-id-usuario="'. $requisitante->id .'"'
            ) ?></li>
            <li><?php echo anchor(
                '/curso/aluno/recusar_requisicao/' . $idCurso,
                'Não Permitir',
                'class="a-recusar-requisicao-inscricao" data-id-usuario="'. $requisitante->id .'"'
            ) ?></li>
        </ul>
    </div>
</li>
<?php endforeach; ?>