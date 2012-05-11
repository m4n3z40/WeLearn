<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados da Resposta</legend>
    <dl>
        <dt><label for="txt-conteudo">Sua réplica:</label></dt>
        <dd><textarea name="conteudo" id="txt-conteudo" cols="60"
                      rows="10"><?php echo $respostaAtual ?></textarea></dd>
    </dl>
</fieldset>
<?php echo form_close() ?>