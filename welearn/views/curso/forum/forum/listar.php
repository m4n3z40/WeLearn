<div id="forum-listar-content">
    <header>
        <hgroup>
            <h1>Lista de Fóruns da Categoria <span><?php echo $categoria->nome; ?></span></h1>
            <h3>Entre no fórum desejado para visualizar a discussão em andamento.</h3>
        </hgroup>
        <p>
            Todas as discussões desta categoria estão listadas abaixo. Participe.
        </p>
        <p>
            Ou, você pode criar outro fórum nesta categoria, <?php echo anchor('/curso/forum/criar/' . $categoria->id, 'clicando aqui!') ?>
        </p>
    </header>
    <div>
        <nav id="nav-filtros-lista-forums">
            <ul>
                <li><?php echo anchor('/curso/forum/listar/' . $categoria->id . '?f=todos', 'Todos os fóruns') ?></li>
                <li><?php echo anchor('/curso/forum/listar/' . $categoria->id . '?f=ativos', 'Somente fóruns ativos') ?></a></li>
                <li><?php echo anchor('/curso/forum/listar/'. $categoria->id . '?f=inativos', 'Somente fóruns inativos') ?></a></li>
            </ul>
        </nav>
        <?php if($haForuns): ?>
            <table id="forum-lista-forums">
                <?php echo $partialLista ?>
            </table>
            <footer id="paginacao-forum-lista">
            <?php if($haMaisPaginas): ?>
                <a href="#" data-proximo="<?php echo $inicioProxPagina ?>"
                   data-id-categoria="<?php echo $categoria->id ?>" class="button">Fóruns anteriores</a>
            <?php else: ?>
                <h4>Não há mais fóruns a serem exibidos.</h4>
            <?php endif; ?>
            </footer>
        <?php else: ?>
            <h4>Nenhuma discussão foi criada nesta categoria até o momento,
                <?php echo anchor('/curso/forum/criar/' . $categoria->id, 'Seja o primeiro!') ?></h4>
        <?php endif; ?>
    </div>
</div>