<h3>Imagem do Perfil</h3>
<?php echo form_open_multipart('', $extraOpenForm) ?>
    <fieldset>
        <legend>Imagem do Usuário</legend>
        <dl>
            <dt><label for="fil-imagem">Escolha a imagem do seu perfil</label></dt>
                <dd><?php echo form_upload('imagemUsuario', $imagemUsuarioAtual, 'id="fil-imagem"') ?></dd>
        </dl>
    </fieldset>
<?php echo form_close() ?>
 
