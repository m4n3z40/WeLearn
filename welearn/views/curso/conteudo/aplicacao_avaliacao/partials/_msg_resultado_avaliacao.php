<div>
    <?php if ( $controleAvaliacao->situacaoAprovado ): ?>
    <p>Parabéns!! Você foi <strong>APROVADO</strong> na avaliação
        <em><?php echo $controleAvaliacao->avaliacao->nome ?></em> do módulo
        <?php echo $controleAvaliacao->avaliacao->modulo->nroOrdem ?>.</p>
    <?php else: ?>
    <p>Que pena :(</p>
    <p>Você foi <strong>REPROVADO</strong> na avaliação
        <em><?php echo $controleAvaliacao->avaliacao->nome ?></em> do módulo
        <?php echo $controleAvaliacao->avaliacao->modulo->nroOrdem ?>.</p>
    <?php endif; ?>
    <ul>
        <li><em>Sua Nota: </em><strong><?php echo number_format($controleAvaliacao->nota, 1, ',', '.') ?></strong></li>
        <li><em>Tempo Decorrido: </em><strong><?php echo round($controleAvaliacao->tempoDecorrido) ?> min.</strong></li>
        <li><em>Tentativas: </em><strong><?php echo $controleAvaliacao->qtdTentativas ?></strong></li>
    </ul>
</div>