<div id="forum-listar-categorias-content">
    <header>
        <hgroup>
            <h1>Lista de Categorias de fórum do Curso</h1>
            <h3>Abaixo você verá todas as categorias de fóruns disponíveis</h3>
        </hgroup>
        <p>
            Entre em uma das categorias listadas abaixo para visualizar seus fóruns.
        </p>
    </header>
    <div>
    <?php if ($haCategorias): ?>
        <table id="forum-lista-categorias">
            <tr><th colspan="2"><h3>Categorias</h3></th></tr>
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
