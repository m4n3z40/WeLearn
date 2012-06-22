<?php echo gerar_menu_autorizado(
    array(
        array(
            'uri' => 'curso/forum/' . $idCurso,
            'texto' => 'Visualizar Fóruns',
            'acao' => 'forum/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => 'curso/forum/categoria/' . $idCurso,
            'texto' => 'Configurar Categorias',
            'acao' => 'categoria/index',
            'papel' => $papelUsuarioAtual
        ),
    ),
    array('<li>','</li>'),
    array('<h3>Ações em Fórum</h3><ul>','</ul>')
);
