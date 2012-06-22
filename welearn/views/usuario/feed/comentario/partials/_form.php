<?php echo form_open($formAction,$formExtra) ?>
<fieldset>
    <legend>Postar Comentário</legend>
    <dl>
        <input type='hidden' name='id-feed-comentario' id='id-feed-comentario'/>
        <dt><label for="txt-comentario"><?=$usuarioAutenticado->toHTML('imagem_mini_sem_link')?></label></dt>
        <dd><textarea  name="txtComentario" id="txt-comentario" cols="60"rows="1"></textarea></dd>
    </dl>
    <button type="submit" id="comentario-submit">Comentar</button>
</fieldset>
<?php echo form_close() ?>