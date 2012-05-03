<?php foreach ($listaComentarios as $comentario): ?>
<li>
    <article>
        <aside>
            <header>
            <?php if ($comentario->criador->imagem): ?>
                <?php echo $comentario->criador->imagem ?>
            <?php endif; ?>
            </header>
            <div>
                <p>
                    Autor: <span><?php echo anchor('/usuario/' . $comentario->criador->id, $comentario->criador->nome) ?></span><br>
                    Criado em: <span><?php echo date('d/m/Y, à\s H:i:s', $comentario->dataEnvio) ?></span><br>
                    <?php if ($comentario->dataAlteracao): ?>
                    Alterado em: <span><?php echo date('d/m/Y, à\s H:i:s', $comentario->dataAlteracao) ?></span>
                    <?php else: ?>
                    Nunca foi alterado.
                    <?php endif; ?>
                </p>
            </div>
            <footer>
                <nav>
                    <ul>
                        <li><?php echo anchor('/conteudo/comentario/alterar/' . $comentario->id, 'Alterar Comentário', 'class="a-alterar-comentario"') ?></a></li>
                        <li><?php echo anchor('/conteudo/comentario/remover/' . $comentario->id, 'Remover Comentário', 'class="a-remover-comentario"') ?></li>
                    </ul>
                </nav>
            </footer>
        </aside>
        <div>
            <?php if ($comentario->assunto): ?>
                <h4><?php echo $comentario->assunto ?></h4>
            <?php endif; ?>
            <p><?php echo nl2br($comentario->txtComentario) ?></p>
        </div>
    </article>
</li>
<?php endforeach; ?>