<div id="exibicao-avaliacao-content">
    <header>
        <h1>Você ainda não pode avançar!</h1>
        <h3>Existe uma avaliação pendente de aprovação para que você continue avançando no curso.</h3>
    </header>
    <div>
        <h3>Informações sobre a avaliação atual</h3>
        <ul>
            <li><strong>Nome:</strong> <?php echo $controleAvaliacao->avaliacao->nome ?></li>
            <li><strong>Pertence ao Módulo <?php echo $controleAvaliacao->avaliacao->modulo->nroOrdem ?>:</strong>
                <?php echo $controleAvaliacao->avaliacao->modulo->nome ?></li>
            <li><strong>Nota mínima necessária para aprovação: </strong> <?php echo number_format($controleAvaliacao->avaliacao->notaMinima, 1, ',', '.') ?></li>
            <li><strong>Qtd. de Tentativas Permitidas: </strong>
                <?php echo $controleAvaliacao->avaliacao->qtdTentativasPermitidas === 0
                           ? 'Sem Limites'
                           : $controleAvaliacao->avaliacao->qtdTentativasPermitidas ?></li>
            <li><strong>Qtd. de Questões: </strong> <?php echo $controleAvaliacao->avaliacao->qtdQuestoesExibir ?></li>
        </ul>
        <?php echo anchor(
            '/curso/conteudo/aplicacao_avaliacao/' . $controleAvaliacao->avaliacao->modulo->curso->id,
            'Ir para a Avaliação',
            'class="button" target="_parent"'
        ) ?>
    </div>
</div>