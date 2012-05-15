<?php foreach ($listaAlunos as $aluno): ?>
<li>
    <div>
        <?php echo $aluno->toHTML('imagem_pequena') ?>
        <ul>
            <li><?php echo anchor(
                '/curso/aluno/desvincular/' . $idCurso,
                'Desvincular aluno',
                'class="a-desvincular-aluno" data-id-aluno="'. $aluno->id .'"'
            ) ?></li>
        </ul>
    </div>
</li>
<?php endforeach; ?>