<div id="listar-index-content">
    <header>
        <hgroup>
            <h1>Listando Alunos do Curso</h1>
            <h3>Aqui você poderá ficar atualizado sobre quem são os alunos do curso</h3>
        </hgroup>
        <p>Aqui está sendo listado todos os usuários que se inscreveram e estão ativos neste curso!</p>
    </header>
    <div>
    <?php if ($haAlunos): ?>
        <h4>Exibindo <em id="em-qtd-alunos"><?php echo $qtdAlunos ?></em> de <em id="em-total-alunos"><?php echo $totalAlunos ?></em> Aluno(s).</h4>
        <ul id="ul-lista-alunos" class="ul-grid-cursos-alunos">
            <?php echo $listaAlunos ?>
        </ul>
        <?php if ($haMaisPaginas): ?>
            <a href="#" id="a-paginacao-alunos" data-id-curso="<?php echo $idCurso ?>" data-proximo="<?php echo $idProximo ?>">Mais alunos...</a>
        <?php else: ?>
            <h4>Não há mais alunos a serem exibidos.</h4>
        <?php endif; ?>
    <?php else: ?>
        <h4>Não há alunos inscritos neste curso até o momento.</h4>
    <?php endif; ?>
    </div>
</div>