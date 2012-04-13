<?php
/**
 * Created by JetBrains PhpStorm.
 * User: thiago
 * Date: 06/04/12
 * Time: 15:08
 * To change this template use File | Settings | File Templates.
 */
?>
<fieldset>
    <?php echo form_open('mensagem/criar',array('id'=>'form-criar-mensagem')) ?>
    <?php echo form_hidden('destinatario',$idDestinatario) ?>
    <textarea rows="10" cols="50" name='mensagem' id='txt-mensagem'></textarea>
    </br>
    <?php echo form_submit(array('id'=>'btn-form-mensagem','content'=>'enviar','value'=>'enviar'))?>
    <?php echo form_close() ?>
</fieldset>
