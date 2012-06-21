<div id="gerenciador-convites-content">
    <header>
        <hgroup>
            <h1>Convites para Gerenciamento do Curso</h1>
            <h3>Aqui você poderá convidar usuários do serviço para colaborar no gerenciamento do curso</h3>
        </hgroup>
        <p>Aqui você está vendo todas os convites que se encontram pendentes até o momento.</p>
        <p>Deseja convidar usuários para ajudá-lo no gerenciamento do curso? <?php echo anchor('/curso/gerenciador/convidar/' . $idCurso, 'Clique Aqui!') ?></p>
    </header>
    <div>
    <?php if ($haConvites): ?>
        <h4>Exibindo <em class="em-qtd-convites"><?php echo $qtdConvites ?></em> de <em class="em-total-convites"><?php echo $totalConvites ?></em> Convite(s).</h4>
        <ul id="ul-lista-convites" class="ul-grid-cursos-alunos">
            <?php echo $listaConvites ?>
        </ul>
        <?php if ($haMaisPaginas): ?>
            <a href="#" id="a-paginacao-convites" data-id-curso="<?php echo $idCurso ?>" data-proximo="<?php echo $idProximo ?>">Mais convites...</a>
        <?php else: ?>
            <h4>Não há mais convites a serem exibidos.</h4>
        <?php endif; ?>
    <?php else: ?>
        <h4>Atualmente não há convites pendentes. <?php echo anchor('/curso/gerenciador/convidar/' . $idCurso, 'Convide Usuários para Ajudá-lo!') ?></h4>
    <?php endif; ?>
    </div>
</div>