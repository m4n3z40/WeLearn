<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados do Certificado</legend>
    <dl>
        <dt><label for="txt-descricao">Texto de Congratulações:</label></dt>
        <dd><textarea name="descricao"
                      id="txt-descricao"
                      cols="60"
                      rows="10"><?php echo $descricaoAtual ?></textarea>
        <dt><span>Tornar ativo ao enviar?</span></dt>
        <dd>
            <input type="radio"
                   name="ativo"
                   value="1"
                   id="rdo-ativo"
                <?php echo $isAtivo ? 'checked="checked"' : '' ?>>
            <label for="rdo-ativo">Sim</label>
            <input type="radio"
                   name="ativo"
                   value="0"
                   id="rdo-inativo"
                <?php echo !$isAtivo ? 'checked="checked"' : '' ?>>
            <label for="rdo-inativo">Não</label>
        </dd>
    </dl>
</fieldset>
<?php if ( ! $imagemAtual ): ?>
<fieldset>
    <legend>Upload da Imagem</legend>
    <div id="div-imagem-selecionada" style="display:none;"></div>
    <dl>
        <dt><label for="fil-imagem">Escolha a Imagem</label></dt>
        <dd><input type="file" name="imagemRecurso" id="fil-imagem"></dd>
    </dl>
</fieldset>
<?php else: ?>
<div>
    <h4>Imagem Atual</h4>
    <?php echo $imagemAtual ?>
</div>
<?php endif; ?>
<button type="submit" id="btn-form-certificado"><?php echo $txtBotaoEnviar ?></button>
<?php echo form_close() ?>