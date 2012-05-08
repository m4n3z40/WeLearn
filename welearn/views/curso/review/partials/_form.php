<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados da sua avaliação sobre o curso</legend>
    <dl>
        <dt><label for="txt-conteudo">Sua opinião (análise):</label></dt>
        <dd><textarea name="conteudo" id="txt-conteudo" cols="60" rows="10" ><?php echo $conteudoAtual ?></textarea></dd>
        <dt><label for="nbr-qualidade">De uma nota de 0 a 10 para a qualidade deste curso:</label></dt>
        <dd>
            <input type="number" name="qualidade" id="nbr-qualidade" value="<?php echo $qualidadeAtual ?>" min="0" max="10">
            <div id="div-slider-qualidade"></div>
        </dd>
        <dt><label for="nbr-dificuldade">De uma nota de 0 a 10 para a dificuldade deste curso:</label></dt>
        <dd>
            <input type="number" name="dificuldade" id="nbr-dificuldade" value="<?php echo $dificuldadeAtual ?>" min="0" max="10">
            <div id="div-slider-dificuldade"></div>
        </dd>
    </dl>
    <button type="submit" id="btn-form-review"><?php echo $txtBotaoSalvar ?></button>
</fieldset>
<?php echo form_close() ?>