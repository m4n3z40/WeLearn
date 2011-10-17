<fieldset id="fds-imagem">
    <legend>Imagem de Exibição do Curso</legend>
    <dl>
        <?php if (!empty($imagemAtual)): ?>
        <dt><span>Imagem Atual</span></dt>
        <dd><?php echo $imagemAtual ?></dd>
        <?php endif ?>
        <dt><label for="fil-imagem">Escolha</label></dt>
        <dd>
            <div id="upload-img-holder" style="display: none;"></div>
            <input type="file" name="imagem" id="fil-imagem"/>
        </dd>
    </dl>
</fieldset>
