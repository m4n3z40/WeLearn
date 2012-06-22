<h3>Dados Principais</h3>
<?php echo form_open($formAction, $extraOpenForm) ?>
<fieldset>
    <legend>Dados Principais</legend>
    <dl>
        <dt><label for="txt-nome">Nome</label></dt>
        <dd><input type="text" name="nome" id="txt-nome" value="<?php echo $nome ?>" /></dd>
        <dt><label for="txt-sobrenome">SobreNome</label></dt>
        <dd><input type="text" name="sobrenome" id="txt-sobrenome" value="<?php echo $sobreNome ?>" /></dd>
        <dt><label for="txt-senha-atual">Senha Atual</label></dt>
        <dd><input type="password" name="senhaAtual" id="txt-senha-atual" value="" /></dd>
        <dt><label for="txt-senha-nova">Nova Senha</label></dt>
        <dd><input type="password" name="senha" id="txt-senha-nova" value="" /></dd>
        <dt><label for="txt-senha-confirmar">Confirmar Senha</label></dt>
        <dd><input type="password" name="senhaConfirm" id="txt-senha-confirmar" value="" /></dd>
    </dl>
</fieldset>
<fieldset>
    <legend>Área de Interesse</legend>
    <dl>
        <dt><label for="slt-area">Área de Interesse</label></dt>
        <dd><?php echo form_dropdown('area', $listaAreas, $areaAtual, 'id="slt-area"') ?></dd>
        <dt><label for="slt-segmento">Segmento de Interesse</label></dt>
        <dd><?php echo form_dropdown('segmento', $listaSegmentos, $segmentoAtual, 'id="slt-segmento"') ?></dd>
    </dl>
</fieldset>
<?php echo form_close() ?>