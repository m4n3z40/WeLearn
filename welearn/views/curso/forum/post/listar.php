<div id="post-forum-listar-content">
    <header>
        <hgroup>
            <h1>Lista de Posts de Fórum</h1>
            <h3><?php echo $forum->titulo ?></h3>
        </hgroup>
        <p><?php echo nl2br($forum->descricao) ?></p>
        <ul>
            <li>Criado por: <span><?php echo $forum->criador->toHTML('imagem_pequena') ?></span></li>
            <li>Criado em: <span><?php echo date('d/m/Y, à\s H:i:s', $forum->dataCriacao) ?></span></li>
            <li>Nº de posts: <span><?php echo $forum->qtdPosts ?></span></li>
        </ul>
        <?php echo gerar_menu_autorizado(
            array(
                array(
                    'uri' => '/curso/forum/alterar_status/' . $forum->id,
                    'texto' => ($forum->status === WeLearn_Cursos_Foruns_StatusForum::ATIVO) ? 'Desativar este fórum' : 'Ativar este fórum',
                    'attr' => 'class="a-alterarstatus-forum"',
                    'acao' => 'forum/alterar_status',
                    'papel' => $papelUsuarioAtual
                ),
                array(
                    'uri' => '/curso/forum/alterar/' . $forum->id,
                    'texto' => 'Alterar título e descrição',
                    'attr' => 'class="a-alterar-forum"',
                    'acao' => 'forum/alterar',
                    'papel' => $papelUsuarioAtual,
                    'autor' => $forum->criador
                ),
                array(
                    'uri' => '/curso/forum/remover/' . $forum->id,
                    'texto' => 'Excluir este fórum e seus posts',
                    'attr' => 'class="a-remover-forum"',
                    'acao' => 'forum/remover',
                    'papel' => $papelUsuarioAtual,
                    'autor' => $forum->criador
                ),
            ),
            array('<li>','</li>'),
            array('<nav class="forum-adminpanel"><ul>','</ul></nav>')
        ) ?>
        <p>
            <?php echo anchor('/curso/forum/listar/' . $forum->categoria->id, 'Retornar à lista de fóruns da categoria "' . $forum->categoria->nome . '"') ?>
        </p>
    </header>
    <div>
        <?php if($haPosts): ?>
        <header id="paginacao-lista-post">
            <?php if ($haMaisPosts): ?>
            <a href="#" data-proximo="<?php echo $inicioProxPagina ?>"
                   data-id-forum="<?php echo $forum->id ?>" class="button">Posts anteriores</a>
            <?php else: ?>
            <h4>Não há mais posts a serem exibidos.</h4>
            <?php endif; ?>
        </header>
        <ul id="forum-lista-posts">
            <?php echo $listaPosts ?>
        </ul>
        <?php else: ?>
        <h4 id="forum-lista-posts-vazio">Nenhum post foi criado neste fórum até o momento. Aguarde ou <a href="#" class="a-forum-post-criar">Seja o primeiro a postar!</a></h4>
        <?php endif; ?>
        <?php if ( is_autorizado($papelUsuarioAtual, 'post/criar') ): ?>
        <footer>
            <a href="#" class="a-forum-post-criar button big-button">Postar neste fórum</a>
        </footer>
        <?php endif; ?>
    </div>
    <?php if ( is_autorizado($papelUsuarioAtual, 'post/criar') ): ?>
    <hr />
    <div id="form-criar-post-container" style="display: none;">
        <header><a href="#" id="a-fechar-form-criar-post" class="button">Cancelar</a></header>
        <h3>Criar Post</h3>
        <?php echo $formCriar; ?>
    </div>
    <?php endif; ?>
</div>