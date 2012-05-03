<div id="avaliacao-questao-exibir-content">
    <header>
        <hgroup>
            <h1>Exibição de questão da Avaliação <em>"<?php echo $avaliacao->nome ?>"</em></h1>
            <h3>Abaixo é mostrado como será exibido para o aluno cada questão desta
                avaliação</h3>
            <p>Lembrando que o número de alternativas exibidas para cada questão pode ser
                configurado, sendo assim, nesta exibição alguma alternativa incorreta
                pode não aparecer. Além disso a ordem das alternativas é randomizada
                à cada exibição.</p>
        </hgroup>
    </header>
    <div>
        <?php echo $exibicaoQuestao; ?>
    </div>
</div>