<?php foreach ($listaPosts as $post): ?>
<article>
    <aside>
        <header>
        <?php if ($post->criador->imagem): ?>
            <?php echo $post->criador->imagem ?>
        <?php endif; ?>
        </header>
        <div>
            <p>
                Autor: <span><?php echo anchor('/usuario/' . $post->criador->id, $post->criador->nome) ?></span><br>
                Criado em: <span><?php echo date('d/m/Y, à\s H:i:s', $post->dataCriacao) ?></span><br>
                <?php if ($post->dataAlteracao): ?>
                Alterado em: <span><?php echo date('d/m/Y, à\s H:i:s', $post->dataAlteracao) ?></span>
                <?php else: ?>
                Nunca foi alterado.
                <?php endif; ?>
            </p>
        </div>
        <footer>
            <nav>
                <ul>
                    <li><?php echo anchor('/curso/forum/post/alterar/' . $post->id, 'Alterar Post', 'class="a-alterar-post"') ?></a></li>
                    <li><?php echo anchor('/curso/forum/post/remover/' . $post->id, 'Remover Post', 'class="a-remover-post"') ?></li>
                </ul>
            </nav>
        </footer>
    </aside>
    <div>
        <?php if ($post->titulo): ?>
            <h4><?php echo $post->titulo ?></h4>
        <?php endif; ?>
        <p><?php echo nl2br($post->conteudo) ?></p>
    </div>
</article><hr />
<?php endforeach; ?>