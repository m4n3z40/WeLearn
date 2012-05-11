<h4>Resposta do Gerenciador <?php echo anchor('/perfil/' . $gerenciadorId, $gerenciadorNome) ?>:</h4>
<p>
    <?php echo nl2br($conteudoResposta) ?>
</p>
<footer>
    <nav>
        <ul>
            <li><?php echo anchor(
                '/curso/review/alterar_resposta/' . $idReview,
                'Alterar Resposta',
                'class="a-alterar-resposta"'
            ) ?></li>
            <li><?php echo anchor(
                '/curso/review/remover_resposta/' . $idReview,
                'Remover Resposta',
                'class="a-remover-resposta"'
            ) ?></li>
        </ul>
    </nav>
</footer>