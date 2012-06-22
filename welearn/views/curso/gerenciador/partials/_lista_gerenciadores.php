<?php foreach ($listaGerenciadores as $gerenciador): ?>
<li data-id-gerenciador="<?php echo $gerenciador->id ?>">
    <div>
        <?php echo $gerenciador ->toHTML('imagem_pequena') ?>
    </div>
    <?php echo gerar_menu_autorizado(
        array(
            array(
                'uri' => '/curso/gerenciador/desvincular/' . $idCurso,
                'texto' => 'Revogar Cargo de Gerenciador',
                'attr' => 'class="a-desvincular-gerenciador"',
                'acao' => 'gerenciador/desvincular',
                'papel' => $papelUsuarioAtual
            )
        ),
        array('<li>', '</li>'),
        array('<ul>','</ul>')
    ) ?>
</li>
<?php endforeach; ?>