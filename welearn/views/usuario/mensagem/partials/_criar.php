<?php echo form_open('usuario/mensagem/criar',array('id'=>'form-criar-mensagem')) ?>
<fieldset>
    <legend>Envio de Mensagens</legend>
    <?php echo form_hidden('destinatario',$idDestinatario) ?>
    <dl>
        <dt><label for="txt-mensagem">Enviar Mensagem</label></dt>
        <dd><textarea rows="10" cols="50" name='mensagem' id='txt-mensagem'></textarea></dd>
    </dl>
    <?php echo form_submit(array('id'=>'btn-form-mensagem','content'=>'enviar','value'=>'Enviar'))?>
</fieldset>
<?php echo form_close() ?>



