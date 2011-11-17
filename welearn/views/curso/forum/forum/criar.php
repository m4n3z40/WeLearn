<div id="forum-criar-content">
    <header>
        <hgroup>
            <h1>Criar um Fórum de Discussão</h1>
            <h3>Entre com os dados necessários para inicialização do fórum</h3>
        </hgroup>
        <p>
            Fóruns servem para discussão de assuntos diversos. São muito úteis para troca de idéias entre alunos e instrutores!
        </p>
    </header>
    <div>
        <?php echo form_open($formAction, $extraOpenForm, $hiddenFormData) ?>
            <?php echo $formCriar ?>
            <button type="submit" id="btn-form-forum"><?php echo $textoBotaoSubmit ?></button>
        <?php echo form_close() ?>
    </div>
</div>
 
