<div id="forum-listar-content">
    <header>
        <hgroup>
            <h1>Lista de Fóruns da Categoria <span><?php echo $categoria->nome; echo $tituloLista ?></span></h1>
            <h3>Entre no fórum desejado para visualizar a discussão em andamento.</h3>
        </hgroup>
        <p>
            Todas as discussões desta categoria estão listadas abaixo. Participe.
        </p>
        <?php if ( $alunoAutorizado ): ?>
        <?php echo gerar_menu_autorizado(array(
            array(
                'uri' => '/curso/forum/criar/' . $categoria->id,
                'texto' => 'clicando aqui!',
                'acao' => 'forum/criar',
                'papel' => $papelUsuarioAtual
            )
        ), array('<p>Ou, você pode criar outro fórum nesta categoria, ', '</p>')) ?>
        <?php endif; ?>
        <nav id="nav-filtros-lista-forums">
            <ul>
                <li><?php echo anchor('/curso/forum/listar/' . $categoria->id . '?f=todos', 'Todos os fóruns') ?> -
                    <span>(<?php echo $qtdTodos ?>)</span></li>
                <li><?php echo anchor('/curso/forum/listar/' . $categoria->id . '?f=ativos', 'Somente fóruns ativos') ?> -
                    <span>(<?php echo $qtdAtivos ?>)</span></a></li>
                <li><?php echo anchor('/curso/forum/listar/'. $categoria->id . '?f=inativos', 'Somente fóruns inativos') ?> -
                    <span>(<?php echo $qtdInativos ?>)</span></a></li>
            </ul>
        </nav>
    </header>
    <div>
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
            <?php if ( $alunoAutorizado ): ?>
            <?php echo gerar_menu_autorizado(array(
                array(
                    'uri' => '/curso/forum/criar/' . $categoria->id,
                    'texto' => 'Seja o primeiro!',
                    'acao' => 'forum/criar',
                    'papel' => $papelUsuarioAtual
                )
            ), array('<p>Ou, você pode criar outro fórum nesta categoria, ', '</p>')) ?>
            <?php endif; ?></h4>
    <?php endif; ?>
    </div>
</div>