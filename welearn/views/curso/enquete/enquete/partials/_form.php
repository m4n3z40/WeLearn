<?php echo form_open($formAction, $extraOpenForm, $hiddenFormData) ?>
    <fieldset>
        <legend>Abaixo entre com a questão da enquete</legend>
        <dl>
            <dt><label for="txt-questao">Questão</label></dt>
            <dd><textarea name="questao" id="txt-questao" cols="50" rows="5"><?php echo $questaoAtual ?></textarea></dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>Abaixo adicione as alternativas da enquete</legend>
        <ol id="ol-criar-enquete-alternativas">
        <?php if ($enquete): ?>
            <?php foreach ($enquete->alternativas as $alternativa): ?>
            <li>
                <input type="text"
                       name="alternativas[]"
                       value="<?php echo $alternativa->txtAlternativa ?>"
                       id="txt-alternativa-enquete-<?php echo ++$i ?>"
                       placeholder="Entre com a alternativa <?php echo ++$i ?>">
            </li>
            <?php endforeach; ?>
        <?php endif; ?>
        </ol>
        <footer>
            <p>
                <a href="#" class="button" id="btn-adicionar-alternativa">Adicionar Alternativa</a>
                <a href="#" class="button" id="btn-remover-alternativa">Remover Alternativa</a>
                <br>
                <span class="obs">* A enquete deve ter entre 2 e 10 alternativas</span>
            </p>
        </footer>
    </fieldset>
    <fieldset>
        <legend>Abaixo entre indique a data de expiração (fechamento) da enquete.</legend>
        <dl>
            <dt><label for="txt-data-expiracao">Data de Expiração</label></dt>
            <dd><input type="text" name="dataExpiracao" value="<?php echo $dataExpiracaoAtual ?>" id="txt-data-expiracao"></dd>
        </dl>
    </fieldset>
    <button type="submit" id="btn-form-enquete"><?php echo $txtBotaoEnviar ?></button>
<?php echo form_close() ?>