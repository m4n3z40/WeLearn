<?php foreach ($listaComentarios as $comentario): ?>
<li>
    <article>
        <aside>
            <header>
                <?php echo $comentario->criador->toHTML('imagem_pequena') ?>
            </header>
            <div>
                <p>
                    Criado em: <span><?php echo date('d/m/Y, à\s H:i:s', $comentario->dataEnvio) ?></span><br>
                    <?php if ($comentario->dataAlteracao): ?>
                    Alterado em: <span><?php echo date('d/m/Y, à\s H:i:s', $comentario->dataAlteracao) ?></span>
                    <?php else: ?>
                    Nunca foi alterado.
                    <?php endif; ?>
                </p>
            </div>
            <?php echo gerar_menu_autorizado(
                array(
                    array(
                        'uri' => '/conteudo/comentario/alterar/' . $comentario->id,
                        'texto' => 'Alterar Comentário',
                        'attr' => 'class="a-alterar-comentario"',
                        'autor' => $comentario->criador
                    ),
                    array(
                        'uri' => '/conteudo/comentario/remover/' . $comentario->id,
                        'texto' => 'Remover Comentário',
                        'attr' => 'class="a-remover-comentario"',
                        'autor' => $comentario->criador,
                        'acao' => 'comentario/remover',
                        'papel' => $papelUsuarioAtual
                    )
                ),
                array('<li>','</li>'),
                array('<footer><nav><ul>', '</ul></nav></footer>')
            ) ?>
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