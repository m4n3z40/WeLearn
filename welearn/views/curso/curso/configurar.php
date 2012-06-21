<div id="curso-config-content">
    <header>
        <hgroup>
            <h1>Configurar Curso</h1>
            <h3>Configure o curso como desejar. Você é o gerenciador!</h3>
        </hgroup>
        <p>
            Toda configuração feita aqui, não é permanente,
            então sinta-se livre para experimentar a configuração ideal para o curso.
        </p>
    </header>
    <div id="curso-config-form-container">
        <header>
            <nav id="curso-config-form-tab">
                <ul>
                    <li><a href="#fds-dados-principais">Dados Principais</a></li>
                    <li><a href="#fds-imagem">Imagem do Curso</a></li>
                    <li><a href="#fds-config-avancadas">Configurações Avançadas</a></li>
                </ul>
            </nav>
        </header>
        <div id="div-form-configurar-wrapper">
            <?php echo form_open_multipart($formAction, $extraOpenForm, $hiddenFormData) ?>
                <div id="curso-config-form-wraper" style="display: none;">
                    <?php echo $formDadosPrincipais ?>
                    <?php echo $formImagem ?>
                    <?php echo $formConfiguracoesAvancadas ?>
                    <button type="submit" id="btn-config-curso" style="display: none"><?php echo $textoBotaoSubmit ?></button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
