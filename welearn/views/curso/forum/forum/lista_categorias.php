<div id="forum-listar-categorias-content">
    <header>
        <hgroup>
            <h1>Lista de Categorias de fórum do Curso</h1>
            <h3>Abaixo você verá todas as categorias de fóruns disponíveis</h3>
        </hgroup>
        <p>
            Entre em uma das categorias listadas abaixo para visualizar seus fóruns.
        </p>
        <p>
            Ou, você pode criar outra categoria de fóruns, <?php echo anchor('/curso/forum/categoria/criar/' . $idCurso, 'clicando aqui!') ?>
        </p>
    </header>
    <div>
    <?php if ($haCategorias): ?>
        <h3>Categorias</h3>
        <table id="forum-lista-categorias">
            <?php echo $listaCategorias ?>
        </table>
        <footer>
            <nav id="paginacao-forum-categorias">
            <?php if ($haMaisPaginas): ?>
                <a href="#" data-proximo="<?php echo $inicioProxPagina ?>" data-id-curso="<?php echo $idCurso ?>" class="button">Categoria mais antigas</a>
            <?php else: ?>
                <h4>Não há mais categorias a serem exibidas.</h4>
            <?php endif; ?>
            </nav>
        </footer>
    <?php else: ?>
        <h4>Nenhuma categoria de fórum foi criada até o momento.</h4>
    <?php endif; ?>
    </div>
</div>
