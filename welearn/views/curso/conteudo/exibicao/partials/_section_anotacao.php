<div id="div-anotacao-form-container">
    <?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
    <pre><?php echo $anotacaoAtual ? $anotacaoAtual->conteudo : 'Salve uma anotação sobre esta página aqui.' ?></pre>
    <textarea name="anotacao"
              id="txt-anotacao"
              style="display: none;"
              cols="60"
              rows="3"
              placeholder=""><?php echo $anotacaoAtual ? $anotacaoAtual->conteudo : '' ?></textarea>
    <?php echo form_close() ?>
</div>