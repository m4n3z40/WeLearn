<?php foreach ($listaResenhas as $resenha): ?>
<li>
    <article>
        <header>
            <h4>Avaliação do aluno <?php echo anchor('/perfil/' . $resenha->criador->id,
                                                      $resenha->criador->nome) ?></h4>
            <ul>
                <li>Enviado em: <em><?php echo date('d/m/Y à\s H:i:s', $resenha->dataEnvio) ?></em></li>
                <li>Nota para a qualidade deste curso:
                    <strong><?php echo $resenha->qualidade ?></strong></li>
                <li>Nota para a dificuldade deste curso:
                    <strong><?php echo $resenha->dificuldade ?></strong></li>
            </ul>
        </header>
        <div>
            <h4>Opinião do Aluno:</h4>
            <p>
                <?php echo nl2br($resenha->conteudo) ?>
            </p>
        </div>
        <div <?php echo  ( ! $resenha->resposta ) ? 'style="display: none;"' : ''?>>
            <?php if ($resenha->resposta): ?>
            <h4>Resposta do Gerenciador
                <?php echo anchor('/perfil/' . $resenha->resposta->criador->id,
                    $resenha->resposta->criador->nome) ?>:</h4>
            <p>
                <?php echo nl2br($resenha->resposta->conteudo) ?>
            </p>
            <footer>
                <nav>
                    <ul>
                        <li><?php echo anchor(
                            '/curso/review/alterar_resposta/' . $resenha->id,
                            'Alterar Resposta',
                            'class="a-alterar-resposta"'
                        ) ?></li>
                        <li><?php echo anchor(
                            '/curso/review/remover_resposta/' . $resenha->id,
                            'Remover Resposta',
                            'class="a-remover-resposta"'
                        ) ?></li>
                    </ul>
                </nav>
            </footer>
            <?php endif; ?>
        </div>
        <footer>
            <nav>
                <ul>
                    <li><?php echo anchor(
                        '/curso/review/alterar/' . $resenha->id,
                        'Alterar',
                        'class="a-alterar-review"'
                    ) ?></li>
                    <li><?php echo anchor(
                        '/curso/review/remover/' . $resenha->id,
                        'Remover',
                        'class="a-remover-review"'
                    ) ?></li>
                    <?php if ( ! $resenha->resposta ): ?>
                    <li><?php echo anchor(
                        '/curso/review/responder/' . $resenha->id,
                        'Responder à Avaliação',
                        'class="a-responder-review"'
                    ) ?></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </footer>
    </article>
</li>
<?php endforeach; ?>
