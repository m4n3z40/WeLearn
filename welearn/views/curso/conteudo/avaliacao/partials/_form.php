<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados da Avaliação</legend>
    <dl>
        <dt><label for="txt-nome">Nome:</label></dt>
        <dd><input type="text" id="txt-nome" name="nome" value="<?php echo $nomeAtual ?>"></dd>
        <dt><label for="nbr-nota-minima">Nota Mínima Satisfatória (de 0 a 10):</label></dt>
        <dd><input type="number" id="nbr-nota-minima" name="notaMinima"
                   value="<?php echo $notaMinimaAtual ?>" min="0" max="10"
                   step="0.5"></dd>
        <dt><label for="nbr-max-duracao">Tempo Máximo de Duração (em minutos):</label></dt>
        <dd><input type="number" id="nbr-max-duracao" name="tempoDuracaoMax"
                   value="<?php echo $tempoDuracaoMaxAtual ?>" min="0"
                   max="180">
            <br>
            <span>Obs: O valor '0' significará que a prova não haverá limite de tempo.</span>
        </dd>
        <dt><label for="slt-qtd-tentativas">Quantidade de Tentativas Permitidas:</label></dt>
        <dd>
            <select name="qtdTentativasPermitidas" id="slt-qtd-tentativas">
                <option value="0" <?php echo ($qtdTentativasPermitidasAtual == 0)
                    ? 'selected="selected"' : '' ?>>Sem limites</option>
                <option value="1" <?php echo ($qtdTentativasPermitidasAtual == 1)
                    ? 'selected="selected"' : '' ?>>1 Tentativa</option>
                <option value="2" <?php echo ($qtdTentativasPermitidasAtual == 2)
                    ? 'selected="selected"' : '' ?>>2 Tentativas</option>
                <option value="3" <?php echo ($qtdTentativasPermitidasAtual == 3)
                    ? 'selected="selected"' : '' ?>>3 Tentativas</option>
                <option value="4" <?php echo ($qtdTentativasPermitidasAtual == 4)
                    ? 'selected="selected"' : '' ?>>4 Tentativas</option>
                <option value="5" <?php echo ($qtdTentativasPermitidasAtual == 5)
                    ? 'selected="selected"' : '' ?>>5 Tentativas</option>
            </select>
        </dd>
    </dl>
</fieldset>
<button type="submit" id="btn-form-avaliacao"><?php echo $txtBotaoEnviar ?></button>
<?php echo form_close() ?>