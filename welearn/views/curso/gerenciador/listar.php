<div id="gerenciador-convites-content">
    <header>
        <hgroup>
            <h1>Gerenciadores Auxiliares do Curso</h1>
            <h3>Aqui você poderá visualizar quem são os gerenciadores do curso
                e até revogar seus cargos de gerenciadores!</h3>
        </hgroup>
        <p>os Gerenciadores colaboram para a administração do curso, eles não possuem
            mais privilégios que o próprio Criador do curso, mais tem bem mais
            privilégios que os Alunos.</p>
        <p>Quer convidar mais usuários para colaborar com o gerenciamento deste Curso?
            <?php echo anchor('/curso/gerenciador/convites/' . $idCurso, 'Clique aqui!') ?></p>
    </header>
    <div>
    <?php if ($haGerenciadores): ?>
        <h4>Exibindo <em class="em-qtd-gerenciadores"><?php echo $qtdGerenciadores ?></em> de <em class="em-total-gerenciadores"><?php echo $totalGerenciadores ?></em> Gerenciadores do Curso.</h4>
        <ul id="ul-lista-gerenciadores" class="ul-grid-cursos-alunos">
            <?php echo $listaGerenciadores ?>
        </ul>
        <?php if ($haMaisPaginas): ?>
            <a href="#" id="a-paginacao-gerenciadores" data-id-curso="<?php echo $idCurso ?>" data-proximo="<?php echo $idProximo ?>">Mais Gerenciadores...</a>
        <?php else: ?>
            <h4>Não há mais gerenciadores auxiliares a serem exibidos.</h4>
        <?php endif; ?>
    <?php else: ?>
        <h4>Atualmente não há gerenciadores colaborando para o gerenciamento deste curso. <?php echo anchor('/curso/gerenciador/convites/' . $idCurso, 'Convide Usuários para Ajudá-lo!') ?></h4>
    <?php endif; ?>
    </div>
</div>