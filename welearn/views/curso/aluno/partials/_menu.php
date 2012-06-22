<?php echo gerar_menu_autorizado(
    array(
        array(
            'uri' => 'curso/aluno/listar/' . $idCurso,
            'texto' => 'Listar Alunos (' . $totalAlunos .')',
            'acao' => 'aluno/listar',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => 'curso/aluno/requisicoes/' . $idCurso,
            'texto' => 'Inscrições em Espera (' . $totalRequisicoes .')',
            'acao' => 'aluno/requisicoes',
            'papel' => $papelUsuarioAtual
        )
    ),
    array('<li>','</li>'),
    array('<h3>Ações em Alunos</h3><ul>','</ul>')
) ?>