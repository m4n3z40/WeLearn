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
        <nav>
            <ul>
                <li><?php echo anchor('/curso/forum/alterar_status/' . $forum->id,
                                      ($forum->status === WeLearn_Cursos_Foruns_StatusForum::ATIVO) ? 'Desativar este fórum' : 'Ativar este fórum',
                                     'class="a-alterarstatus-forum"') ?></li>
                <li><?php echo anchor('/curso/forum/alterar/' . $forum->id, 'Alterar título e descrição', 'class="a-alterar-forum"') ?></li>
                <li><?php echo anchor('/curso/forum/remover/' . $forum->id, 'Excluir este fórum e seus posts', 'class="a-remover-forum"') ?></li>
                <li><?php echo anchor('/curso/forum/listar/' . $forum->categoria->id, 'Retornar à lista de fóruns da categoria "' . $forum->categoria->nome . '"') ?></li>
            </ul>
        </nav>
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
        <footer>
            <a href="#" class="a-forum-post-criar button big-button">Postar neste fórum</a>
        </footer>
    </div>
    <hr />
    <div id="form-criar-post-container" style="display: none;">
        <header><a href="#" id="a-fechar-form-criar-post" class="button">Cancelar</a></header>
        <h3>Criar Post</h3>
        <?php echo $formCriar; ?>
    </div>
</div>