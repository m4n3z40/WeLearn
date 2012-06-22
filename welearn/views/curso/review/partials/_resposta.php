<h4>Resposta do Gerenciador <?php echo anchor('/perfil/' . $gerenciadorId, $gerenciadorNome) ?>:</h4>
<p>
    <?php echo nl2br($conteudoResposta) ?>
</p>
<?php echo gerar_menu_autorizado(
    array(
        array(
            'uri' => '/curso/review/alterar_resposta/' . $idReview,
            'texto' => 'Alterar Resposta',
            'attr' => 'class="a-alterar-resposta"',
            'autor' => $autor
        ),
        array(
            'uri' => '/curso/review/remover_resposta/' . $idReview,
            'texto' => 'Remover Resposta',
            'attr' => 'class="a-remover-resposta"',
            'autor' => $autor,
            'acao' => 'review/remover_resposta',
            'papel' => $papelUsuarioAtual
        ),
    ),
    array('<li>','</li>'),
    array('<footer><nav><ul>','</ul></nav></footer>')
);