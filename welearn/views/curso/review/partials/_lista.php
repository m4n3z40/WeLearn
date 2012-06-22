<?php foreach ($listaResenhas as $resenha): ?>
<li>
    <article>
        <header>
            <h4>Avaliação do aluno <?php echo $resenha->criador->toHTML('somente_link') ?></h4>
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
            <?php echo gerar_menu_autorizado(
                array(
                    array(
                        'uri' => '/curso/review/alterar_resposta/' . $resenha->id,
                        'texto' => 'Alterar Resposta',
                        'attr' => 'class="a-alterar-resposta"',
                        'autor' => $resenha->resposta->criador
                    ),
                    array(
                        'uri' => '/curso/review/remover_resposta/' . $resenha->id,
                        'texto' => 'Remover Resposta',
                        'attr' => 'class="a-remover-resposta"',
                        'autor' => $resenha->resposta->criador,
                        'acao' => 'review/remover_resposta',
                        'papel' => $papelUsuarioAtual
                    ),
                ),
                array('<li>','</li>'),
                array('<footer><nav><ul>','</ul></nav></footer>')
            ) ?>
            <?php endif; ?>
        </div>
        <?php echo gerar_menu_autorizado(
            array(
                array(
                    'uri' => '/curso/review/alterar/' . $resenha->id,
                    'texto' => 'Alterar',
                    'attr' => 'class="a-alterar-review"',
                    'autor' => $resenha->criador
                ),
                array(
                    'uri' => '/curso/review/remover/' . $resenha->id,
                    'texto' => 'Remover',
                    'attr' => 'class="a-remover-review"',
                    'autor' => $resenha->criador
                ),
                array(
                    'uri' => '/curso/review/responder/' . $resenha->id,
                    'texto' => 'Responder à Avaliação',
                    'attr' => 'class="a-responder-review"',
                    'acao' => 'review/remover_resposta',
                    'papel' => $papelUsuarioAtual
                ),
            ),
            array('<li>','</li>'),
            array('<footer><nav><ul>','</ul></nav></footer>')
        ) ?>
    </article>
</li>
<?php endforeach; ?>
