<h3>Imagem do Perfil</h3>
<?php echo form_open_multipart($formAction, $extraOpenForm) ?>
    <?php if ($imagemUsuarioAtual): ?>
    <fieldset>
        <legend>Imagem Atual</legend>
        <figure>
            <?php echo $imagemUsuarioAtual ?>
        </figure>
    </fieldset>
    <?php endif; ?>
    <fieldset>
        <legend>Escolher nova Imagem</legend>
        <dl>
            <dt><label for="fil-imagem">Escolha a imagem do seu perfil</label></dt>
                <dd>
                    <div id="upload-img-holder" style="display: none;"></div>
                    <?php echo form_upload('imagemUsuario', '', 'id="fil-imagem"') ?>
                </dd>
        </dl>
    </fieldset>
<?php echo form_close() ?>