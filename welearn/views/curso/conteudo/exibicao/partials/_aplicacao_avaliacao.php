<div id="aplicacao-avaliacao-content">
    <header>
        <hgroup>
            <h1>Avaliação: <?php echo $avaliacao->nome ?></h1>
            <h3>Aplicando Avaliação do Módulo <?php echo $avaliacao->modulo->nroOrdem ?>: <?php echo $avaliacao->modulo->nome ?></h3>
        </hgroup>
        <table>
            <tr>
                <th>Qtd. de Questões</th>
                <th>Tempo Restante</th>
            </tr>
            <tr>
                <td><?php echo $avaliacao->qtdQuestoesExibir ?></td>
                <td><?php echo $avaliacao->tempoDuracaoMax ?></td>
            </tr>
        </table>
    </header>
    <div>
        <ul id="div-aplicacao-avaliacao" >
            <?php $i = 0; foreach ($avaliacao->questoesRandomizadas as $questao): ?>
            <li id="questao-<?php echo $questao->id ?>" class="li-questao-exibir-questao">
                <div>
                    <div class="div-questao-exibir-enunciado">
                        <h4>Questão <?php echo ++$i ?>:</h4>
                        <pre><?php echo $questao->enunciado ?></pre>
                    </div>
                    <div class="div-questao-exibir-alternativas">
                        <h4>Alternativas:</h4>
                        <ul class="selectable-radios">
                            <?php foreach ($questao->alternativasRandomizadas as $alternativa): ?>
                            <li><input type="radio" name="alternativaEscolhida[<?php echo $questao->id ?>]"
                                       value="<?php echo $alternativa->id ?>"
                                       id="alternativa-<?php echo $alternativa->id ?>">
                                <label for="alternativa-<?php echo $alternativa->id ?>">
                                    <?php echo $alternativa->txtAlternativa ?></label></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>