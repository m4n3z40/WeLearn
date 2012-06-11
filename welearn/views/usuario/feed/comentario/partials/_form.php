<?php echo form_open($formAction,$formExtra) ?>
<fieldset>
    <dl>
        <input type='hidden' name='id-feed-comentario' id='id-feed-comentario'/>
        <dt><label for="txt-comentario">Comentário:</label></dt>
        <dt><?=$usuarioAutenticado->toHTML('imagem_mini')?></dt>
        <dd><textarea  name="txtComentario" id="txt-comentario" cols="60"rows="1"></textarea></dd>
    </dl>
    <button type="submit" id="comentario-submit">postar</button>
</fieldset>
<?php echo form_close() ?>