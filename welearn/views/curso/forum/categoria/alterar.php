<div id="categoria-forum-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar uma categoria de fórum</h1>
            <h3>Entre com os novos dados da categoria.</h3>
        </hgroup>
        <p>
            Não queria estar aqui? <?php echo anchor('curso/forum/categoria/listar/' . $idCurso, 'Volte para a listagem de categorias') ?>
        </p>
    </header>
    <div>
        <?php echo form_open($formAction, $extraOpenForm, $hiddenFormData) ?>
            <?php echo $formAlterar ?>
            <button type="submit" id="btn-form-categoria-forum"><?php echo $textoBotaoSubmit ?></button>
        <?php echo form_close() ?>
    </div>
</div>