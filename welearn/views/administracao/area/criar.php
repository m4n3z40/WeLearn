<div id='form-criar-area'>
    <header>
        <hgroup>
            <h1>Criar uma Nova Área</h1>
            <h3>Entre com os dados necessários para criação da Área</h3>
        </hgroup>
    </header>
    <div>

        <?php echo form_open($formAction, $extraOpenForm, $hideFormDate) ?>
            <?php echo $formCriar ?>
            <button type="submit" id="btn-form-area"><?php echo $textoBotaoSubmit ?></button>
        <?php echo form_close() ?>
    </div>
</div>