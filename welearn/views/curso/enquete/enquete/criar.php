<div id="enquete-criar-content">
    <header>
        <hgroup>
            <h1>Criar uma enquete</h1>
            <h3>Entre com os dados principais da enquete e adicione suas alternativas</h3>
        </hgroup>
        <p>
            Enquetes são úteis para conhecer a opinião dos alunos sobre certos assuntos. Conhecimento é tudo.
        </p>
    </header>
    <div>
    <?php echo form_open($formAction, $extraOpenForm, $hiddenFormData) ?>
        <fieldset>
            <legend>Abaixo entre com a questão da enquete</legend>
            <dl>
                <dt><label for="txt-questao">Questão</label></dt>
                <dd><textarea name="questao" id="txt-questao" cols="50" rows="5"></textarea></dd>
            </dl>
        </fieldset>
        <fieldset>
            <legend>Abaixo adicione as alternativas da enquete</legend>
            <ol id="ol-criar-enquete-alternativas"></ol>
            <footer>
                <p>
                    <a href="#" class="button" id="btn-adicionar-alternativa">Adicionar Alternativa</a>
                    <a href="#" class="button" id="btn-remover-alternativa">Remover Alternativa</a>
                    <br>
                    <span class="obs">* A enquete deve ter entre 2 e 10 alternativas</span>
                </p>
            </footer>
        </fieldset>
        <fieldset>
            <legend>Abaixo entre indique a data de expiração (fechamento) da enquete.</legend>
            <dl>
                <dt><label for="txt-data-expiracao">Data de Expiração</label></dt>
                <dd><input type="text" name="dataExpiracao" id="txt-data-expiracao"></dd>
            </dl>
        </fieldset>
        <button type="submit" id="btn-form-enquete">Enviar e Publicar!</button>
    <?php echo form_close() ?>
    </div>
</div>