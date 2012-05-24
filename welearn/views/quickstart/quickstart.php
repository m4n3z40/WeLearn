<div id="quickstart-container">
    <header>
        <hgroup>
            <h1>Ferramenta de Quickstart</h1>
            <h3>Deixe-nos saber um pouco mais sobre você para que possamos indicá-lo à pessoas e cursos com o seu perfil.</h3>
        </hgroup>
        <p>Caso não queira preencher todos os dados agora, você pode preencher essas informações nas configurações de usuário.</p>
    </header>
    <div id="quickstart-steps">
        <header>
            <h3>Progresso</h3>
            <div id="quickstart-progress">
                <h3></h3>
                <div id="progressbar"></div>
            </div>
            <nav>
                <ul>
                    <li><a href="#" class="quickstart-salvar">Salvar e Avançar</a></li>
                    <li><a href="#" class="quickstart-pular">Pular</a></li>
                    <li><a href="#" class="quickstart-pular-todos">Pular Todos</a></li>
                </ul>
            </nav>
        </header>
        <div>
            <section id="etapa-dados-pessoais" style="display: none;">
                <?php echo $formEtapa1 ?>
            </section>
            <section id="etapa-dados-profissionais" style="display: none;">
                <?php echo $formEtapa2 ?>
            </section>
            <section id="etapa-upload-imagem" style="display: none;">
                <?php echo $formEtapa3 ?>
            </section>
            <section id="etapa-configuracao-privacidade" style="display: none;">
                <?php echo $formEtapa4 ?>
            </section>
        </div>
    </div>
    <footer>
        <nav>
            <ul>
                <li><a href="#" class="quickstart-salvar">Salvar e Avançar</a></li>
                <li><a href="#" class="quickstart-pular">Pular</a></li>
                <li><a href="#" class="quickstart-pular-todos">Pular Todos</a></li>
            </ul>
        </nav>
    </footer>
</div>