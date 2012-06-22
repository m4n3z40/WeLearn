<?php echo gerar_menu_autorizado(
    array(
        array(
            'uri' => '/curso/gerenciador/listar/' . $idCurso,
            'texto' => 'Listar Gerenciadores (' . $totalGerenciadores .')',
            'acao' => 'gerenciador/listar',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/gerenciador/convites/' . $idCurso,
            'texto' => 'Convites de Gerenciamento (' . $totalConvites .')',
            'acao' => 'gerenciador/convites',
            'papel' => $papelUsuarioAtual
        ),
    ),
    array('<li>','</li>'),
    array('<h3>Ações em Fórum</h3><ul>','</ul>')
);