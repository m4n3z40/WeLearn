<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados da Página</legend>
    <dl>
        <dt><label for="txt-nome">Nome:</label></dt>
        <dd><input type="text" name="nome" id="txt-nome" value="<?php echo $nomeAtual ?>"></dd>
        <dt><label for="txt-conteudo">Conteúdo:</label></dt>
        <dd><textarea name="conteudo" id="txt-conteudo" style="width: 800px; height: 500px;"><?php echo $conteudoAtual ?></textarea></dd>
    </dl>
</fieldset>
<?php echo form_close() ?>