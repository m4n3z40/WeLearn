<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados da Aula</legend>
    <dl>
        <dt><label for="txt-nome">Nome:</label></dt>
        <dd><input type="text" name="nome" id="txt-nome" value="<?php echo $nomeAtual ?>"></dd>
        <dt><label for="txt-descricao">Descrição:</label></dt>
        <dd><textarea name="descricao" id="txt-descricao" cols="60"
                      rows="10"><?php echo $descricaoAtual ?></textarea></dd>
    </dl>
</fieldset>
<button type="submit" id="btn-form-aula"><?php echo $txtBotaoEnviar ?></button>
<?php echo form_close() ?>