<?php foreach ($listaPosts as $post): ?>
<li>
    <article>
        <aside>
            <header>
                <?php echo $post->criador->toHTML('imagem_pequena') ?>
            </header>
            <div>
                <p>
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
    </article>
</li>
<?php endforeach; ?>