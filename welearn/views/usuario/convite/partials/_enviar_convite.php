
<div id="convite-form" title="Digite a mensagem do convite" style="display: none;">
<?php echo form_open(base_url().'convite/enviar',array('id'=>'form-enviar-convite'))?>
<?php $options = array(
    'rows' => '5',
    'cols' => '43',
    'name' => 'txt-convite',
);?>
<?php echo form_hidden('destinatario',$usuarioPerfil)?>
<?php echo form_textarea($options)?>
<?php echo form_close()?>
</div>