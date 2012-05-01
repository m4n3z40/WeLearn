
<?=$nome?>
<?=$sobrenome?>
<?=$email?>
<?php echo form_hidden('id',$id)?>
<div id="convite-form" title="Digite sua mensagem" style="display: none;">
    <?php echo form_open(base_url().'convite/enviar',array('id'=>'form-enviar-convite'))?>
   <?php $options = array(
    'rows'  => '7',
    'cols'  => '35',
    'name' => 'txt-convite',
    );?>
    <?php echo form_hidden('destinatario',$id)?>
    <?php echo form_textarea($options)?>
    <?php echo form_submit(array('id'=>'btn-form-convite','content'=>'enviar','value'=>'enviar'))?>
    <?php echo form_close()?>
</div>