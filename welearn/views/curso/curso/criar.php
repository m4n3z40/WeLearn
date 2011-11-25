<div id="curso-criar-content">
    <header>
        <hgroup>
            <h1>Criar um Curso</h1>
            <h3>Preencha os dados básicos do curso para iniciá-lo.</h3>
        </hgroup>
        <p>
            Como criador, você se tornará o Gerenciador Principal do Curso, tendo assim total controle sob o curso criado.
        </p>
    </header>
    <div>
        <?php echo form_open_multipart($formAction, $extraOpenForm, $hiddenFormData) ?>
            <?php echo $formDadosPrincipais ?>
            <?php echo $formConfiguracoesGerais ?>
            <?php echo $formImagem ?>
            <button type="submit" id="btn-form-curso"><?php echo $textoBotaoSubmit ?></button>
        <?php echo form_close() ?>
    </div>
</div>
 
