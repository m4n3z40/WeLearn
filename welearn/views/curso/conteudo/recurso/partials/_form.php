<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados do Recurso</legend>
    <dl>
        <dt><label for="txt-nome">Nome do Recurso:</label></dt>
        <dd><input type="text" name="nome" id="txt-nome" value="<?php echo $nomeAtual ?>"></dd>
        <dt><label for="txt-descricao">Descrição:</label></dt>
        <dd><textarea name="descricao" id="txt-descricao" cols="60" rows="10"><?php echo $descricaoAtual ?></textarea></dd>
        <dt><label for="slt-tipo">Tipo do Recurso:</label></dt>
        <dd>
            <ul>
                <li><?php echo form_dropdown('tipo', $optionsTipo, $tipoAtual, 'id="slt-tipo"') ?></li>
                <li style="display: none;"><?php echo $selectModulos ?></li>
                <li style="display: none;"><?php echo $selectAulas ?></li>
            </ul>
        </dd>
        <dt style="display: none;"><label for="fil-arquivo-recurso">Escolha o arquivo desejado:</label></dt>
        <dd style="display: none;">
            <div id="recurso-upload-preview" style="display: none;"></div>
            <div id="div-arquivo-recurso-container">
                <input type="file" name="arquivoRecurso" id="fil-arquivo-recurso">
            </div>
        </dd>
    </dl>
</fieldset>
<button type="submit" id='btn-form-recurso'><?php echo $txtBotaoEnviar ?></button>
<?php echo form_close() ?>