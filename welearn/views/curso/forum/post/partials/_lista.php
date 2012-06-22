<?php foreach ($listaPosts as $post): ?>
<li>
    <article>
        <aside>
            <header>
                <?php echo $post->criador->toHTML('imagem_pequena') ?>
            </header>
            <ul>
                <li>Criado em: <span><?php echo date('d/m/Y, à\s H:i:s', $post->dataCriacao) ?></span></li>
                <?php if ($post->dataAlteracao): ?>
                <li>Alterado em: <span><?php echo date('d/m/Y, à\s H:i:s', $post->dataAlteracao) ?></span></li>
                <?php else: ?>
                <li>Nunca foi alterado.</li>
                <?php endif; ?>
            </ul>
            <?php echo gerar_menu_autorizado(
                array(
                    array(
                        'uri' => '/curso/forum/post/alterar/' . $post->id,
                        'texto' => 'Alterar Post',
                        'attr' => 'class="a-alterar-post"',
                        'autor' => $post->criador
                    ),
                    array(
                        'uri' => '/curso/forum/post/remover/' . $post->id,
                        'texto' => 'Remover Post',
                        'attr' => 'class="a-remover-post"',
                        'autor' => $post->criador,
                        'acao' => 'post/remover',
                        'papel' => $papelUsuarioAtual
                    ),
                ),
                array('<li>','</li>'),
                array('<footer><nav id="post-forum-adminpanel"><ul>','</ul></nav></footer>')
            ) ?>
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