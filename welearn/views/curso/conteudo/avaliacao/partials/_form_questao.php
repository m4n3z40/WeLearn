<?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
<fieldset>
    <legend>Dados da Questão</legend>
    <dl>
        <dt><label for="txt-enunciado">Enunciado:</label></dt>
        <dd><textarea name="enunciado" id="txt-enunciado" cols="60"
                      rows="8"><?php echo $enunciadoAtual ?></textarea></dd>
        <dt><label for="nbr-qtdAlternativasExibir">Qtd. de Alternativas a serem exibidas:</label></dt>
        <dd><input type="number" name="qtdAlternativasExibir"
                   id="nbr-qtdAlternativasExibir" min="2" max="12"
                   value="<?php echo $qtdAlternativasExibirAtual ?>"></dd>
    </dl>
</fieldset>
<fieldset>
    <legend>Alternativas</legend>
    <dl id="dl-questao-alternativacorretas">
        <dt><label for="txt-alternativa-correta">Alternativa Correta:</label></dt>
        <dd><textarea name="alternativaCorreta" id="txt-alternativa-correta"
                      cols="60"
                      rows="4"><?php echo $alternativaCorretaAtual ?></textarea></dd>
    </dl>
    <hr>
    <dl id="dl-questao-alternativasincorretas">
<?php if ( empty( $alternativasIncorretasAtuais ) ): ?>
        <dt><label for="txt-alternativa-incorreta-1">Alternativa Incorreta 1</label></dt>
        <dd><textarea name="alternativaIncorreta[]" id="txt-alternativa-incorreta-1"
                      cols="60"
                      rows="4"></textarea></dd>
<?php else: ?>
    <?php for ($i = 0; $i < count($alternativasIncorretasAtuais); $i++): ?>
        <dt><label for="txt-alternativa-incorreta-<?php echo $i + 1 ?>">Alternativa Incorreta <?php echo $i + 1 ?></label></dt>
        <dd><textarea name="alternativaIncorreta[]" id="txt-alternativa-incorreta-<?php echo $i + 1 ?>"
                      cols="60"
                      rows="4"><?php echo $alternativasIncorretasAtuais[$i]->txtAlternativa ?></textarea></dd>
    <?php endfor; ?>
<?php endif; ?>
    </dl>
    <?php echo anchor('#', 'Adicionar alternativa incorreta', 'id="a-adicionar-alternativa" class="button"') ?>
    <?php echo anchor('#', 'Remover alternativa incorreta', 'id="a-remover-alternativa" class="button"') ?>
</fieldset>
<?php echo form_close() ?>