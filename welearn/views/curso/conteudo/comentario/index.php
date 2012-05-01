<div id="comentario-index-content">
    <header>
        <hgroup>
            <h1>Comentários de Aulas</h1>
            <h3>Aqui você poderá gerenciar os comentários das aulas de cada módulo.</h3>
        </hgroup>
        <p>Comentários de Aulas estão disponíveis para o aluno em cada página de aulas,
            onde os alunos podem discutir o conteúdo daquela página, agregando muito mais
            informação a ela!</p>
    </header>
    <div>
        <a href="#" id="a-exibir-select-local-comentario"
           style="display: none;">Exibir comentários de outra página</a>
        <nav id="nav-select-local-comentario">
            <ul>
                <li>
                    <h4>Escolha o Módulo:</h4>
                    <?php echo $selectModulos ?>
                </li>
                <li style="display: none;">
                    <h4>Escolha a Aula:</h4>
                    <?php echo $selectAulas ?>
                </li>
                <li style="display: none">
                    <h4>Escolha a Página:</h4>
                    <?php echo $selectPaginas ?>
                </li>
            </ul>
            <a href="#" id="a-esconder-select-local-comentario"
               style="display: none;">Esconder estas opções</a>
        </nav>
        <hr>
        <h4 id="h4-msg-escolher-pagina">Escolha uma página acima para exibir os
            comentários contidos nela.</h4>
        <div id="div-comentario-lista-container" style="display: none;">
            <h4>Exibindo <em id="em-qtd-comentarios">0</em> de
                <em id="em-total-comentarios">0</em> Comentários em Página
                "<em id="em-nome-pagina">Página</em>" da Aula
                "<em id="em-nome-aula">Aula</em>"</h4>
            <div id="div-comentario-lista-listar">
                <header style="display: none;" id="hdr-paginacao-comentarios">
                    <h4 id="h4-msg-sem-mais-paginas">Não há mais comentários a serem
                        exibidos...</h4>
                    <a href="#" id="a-paginacao-comentarios">Comentários mais antigos</a>
                </header>
                <h4 style="display: none;" id="h4-msg-sem-comentarios">
                    Ainda não há comentários nesta página.
                    <a href="#" class="a-exibir-form-criar-comentario">Clique aqui para postar o primeiro!</a>
                </h4>
                <ul style="display: none;" id="ul-lista-comentario"></ul>
            </div>
            <footer>
                <a href="#" class="a-exibir-form-criar-comentario">Postar comentário nesta
                    página</a>
                <div id="div-form-criar-comentario-container" style="display: none;">
                    <?php echo $formCriar ?>
                </div>
            </footer>
        </div>
    </div>
</div>