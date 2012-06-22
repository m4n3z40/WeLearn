<?php echo gerar_menu_autorizado(
    array(
        array(
            'uri' => '/curso/conteudo/modulo/' . $idCurso,
            'texto' => 'Gerenciar Módulos',
            'acao' => 'modulo/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/conteudo/aula/' . $idCurso,
            'texto' => 'Gerenciar Aulas',
            'acao' => 'aula/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/conteudo/avaliacao/' . $idCurso,
            'texto' => 'Gerenciar Avaliações',
            'acao' => 'avaliacao/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/conteudo/recurso/' . $idCurso,
            'texto' => 'Gerenciar Recursos',
            'acao' => 'recurso/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/conteudo/comentario/' . $idCurso,
            'texto' => 'Gerenciar Comentários',
            'acao' => 'comentario/index',
            'papel' => $papelUsuarioAtual
        ),
    ),
    array('<li>','</li>'),
    array('<h3>Ações em Conteúdo</h3><ul>','</ul>')
);