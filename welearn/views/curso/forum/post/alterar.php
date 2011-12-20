<div id="post-forum-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar Post</h1>
            <h3>Escreveu algo errado ou se arrependeu de algo? Eis a sua chance de mudar isso!</h3>
        </hgroup>
        <p>... Ou <?php echo anchor('/curso/forum/post/listar/' . $post->forum->id, 'clique aqui') ?> para voltar à exibição do fórum <strong><?php echo $post->forum->titulo ?></strong>.</p>
    </header>
    <div>
        <?php echo $formAlterar ?>
    </div>
</div>