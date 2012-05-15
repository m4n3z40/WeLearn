

<fieldset>
    <?php echo form_open('usuario/mensagem/criar',array('id'=>'form-criar-mensagem','title' => 'Digite sua mensagem','style' => 'display:none', )) ?>
    <?php echo form_hidden('destinatario',$idDestinatario) ?>
    <textarea rows="5" cols="43" name='mensagem' id='txt-mensagem'></textarea>
    <?php echo form_close() ?>
</fieldset>
