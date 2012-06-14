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
        <div class="div-questao-exibir-enunciado">
            <h4>Enunciado:</h4>
            <pre><?php echo $questao->enunciado ?></pre>
        </div>
        <div class="div-questao-exibir-alternativas">
            <h4>Alternativas:</h4>
            <ul class="selectable-radios">
                <?php foreach ($questao->alternativasRandomizadas as $alternativa): ?>
                <li><input type="radio" name="alternativaEscolhida[<?php echo $questao->id ?>]"
                           value="<?php echo $alternativa->id ?>"
                           id="<?php echo $alternativa->id ?>">
                    <label for="<?php echo $alternativa->id ?>">
                        <?php echo $alternativa->txtAlternativa ?></label></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>