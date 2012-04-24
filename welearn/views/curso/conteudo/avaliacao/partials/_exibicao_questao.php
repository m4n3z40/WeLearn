<div id="questao-<?php echo $questao->id ?>" class="div-questao-exibir-questao">
    <div class="div-questao-exibir-enunciado">
        <input type="hidden" name="questaoId" value="<?php echo $questao->id ?>">
        <h4>Enunciado:</h4>
        <p><?php echo nl2br($questao->enunciado) ?></p>
    </div>
    <div class="div-questao-exibir-alternativas">
        <h4>Alternativas:</h4>
        <ul class="selectable-radios">
            <?php foreach ($questao->alternativasRandomizadas as $alternativa): ?>
            <li><input type="radio" name="alternativaEscolhida[]"
                       value="<?php echo $alternativa->id ?>"
                       id="<?php echo $alternativa->id ?>">
                <label for="<?php echo $alternativa->id ?>">
                    <?php echo $alternativa->txtAlternativa ?></label></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>