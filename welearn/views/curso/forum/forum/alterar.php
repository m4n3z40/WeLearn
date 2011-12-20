<div id="forum-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar um fórum</h1>
            <h3>Entre com os novos dados do fórum.</h3>
        </hgroup>
        <p>
            Não queria estar aqui? <?php echo anchor('curso/forum/post/listar/' . $idForum, 'Clique aqui e vá para exibição do fórum.') ?>
        </p>
    </header>
    <div>
        <?php echo form_open($formAction, $extraOpenForm, $hiddenFormData) ?>
            <?php echo $formAlterar ?>
            <button type="submit" id="btn-form-forum"><?php echo $textoBotaoSubmit ?></button>
        <?php echo form_close() ?>
    </div>
</div>
