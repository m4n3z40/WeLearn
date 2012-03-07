<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
    <fieldset>
        <legend>Conteúdo do Post</legend>
        <dl>
            <dt><label for="txt-titulo">Título</label></dt>
            <dd><input type="text" name="titulo" id="txt-titulo" value="<?php echo $tituloAtual ?>" maxlength="80"></dd>
            <dt><label for="txt-conteudo">Conteúdo</label></dt>
            <dd><textarea name="conteudo" id="txt-conteudo" cols="60" rows="15"><?php echo $conteudoAtual ?></textarea></dd>
        </dl>
    </fieldset>
    <button type="submit" id="btn-form-post"><?php echo $textoBotaoSubmit ?></button>
<?php echo form_close() ?>