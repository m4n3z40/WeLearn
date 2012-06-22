<?php foreach ($listaAlunos as $aluno): ?>
<li>
    <div>
        <?php echo $aluno->toHTML('imagem_pequena') ?>
        <?php echo gerar_menu_autorizado(
            array(
                array(
                    'uri' => '/curso/aluno/desvincular/' . $idCurso,
                    'texto' => 'Desvincular aluno',
                    'attr' => 'class="a-desvincular-aluno" data-id-aluno="'. $aluno->id .'"',
                    'acao' => 'aluno/desvincular',
                    'papel' => $papelUsuarioAtual
                )
            ),
            array('<li>','</li>'),
            array('<ul>','</ul>')
        ) ?>
    </div>
</li>
<?php endforeach; ?>