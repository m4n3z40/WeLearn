<h3>Ações em Alunos</h3>
<ul>
    <li><?php echo anchor('curso/aluno/listar/' . $idCurso, 'Listar Alunos (' . $totalAlunos .')') ?></li>
    <li><?php echo anchor('curso/aluno/requisicoes/' . $idCurso, 'Inscrições em Espera (' . $totalRequisicoes .')'  ) ?></li>
</ul>