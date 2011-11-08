<div id="categoria-forum-criar-content">
    <header>
        <hgroup>
            <h1>Criar uma categoria de fórum</h1>
            <h3>Entre com um descrição para nova categoria.</h3>
        </hgroup>
        <p>
            Categorias servem para agrupar fóruns. Organização nunca é demais.
        </p>
    </header>
    <div>
        <?php echo form_open($formAction, $extraOpenForm, $hiddenFormData) ?>
            <?php echo $formCriar ?>
            <button type="submit" id="btn-form-categoria-forum"><?php echo $textoBotaoSubmit ?></button>
        <?php echo form_close() ?>
    </div>
</div>
 
