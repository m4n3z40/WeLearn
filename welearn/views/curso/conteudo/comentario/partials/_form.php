<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados do Comentário</legend>
    <dl>
        <dt><label for="txt-assunto">Assunto:</label></dt>
        <dd><input type="text" name="assunto" id="txt-assunto"
                   value="<?php echo $assuntoAtual ?>"></dd>
        <dt><label for="txt-comentario">Comentário:</label></dt>
        <dd><textarea name="txtComentario" id="txt-comentario" cols="60"
                      rows="10"><?php echo $txtComentarioAtual ?></textarea>
        </dd>
    </dl>
    <button type="submit" id="<?php echo $idBotaoEnviar ?>"><?php echo $txtBotaoEnviar ?></button>
</fieldset>
<?php echo form_close() ?>