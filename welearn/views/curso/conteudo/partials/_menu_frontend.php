<?php echo gerar_menu_autorizado(
    array(
        array(
            'uri' => '/curso/conteudo/exibicao/' . $idCurso,
            'texto' => 'Sala de Aula',
            'acao' => 'exibicao/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/conteudo/aplicacao_avaliacao/' . $idCurso,
            'texto' => 'Avaliações',
            'acao' => 'aplicacao_avaliacao/index',
            'papel' => $papelUsuarioAtual
        ),
    ),
    array('<li>','</li>'),
    array('<h3>Ações na Sala de Aula</h3><ul>','</ul>')
);