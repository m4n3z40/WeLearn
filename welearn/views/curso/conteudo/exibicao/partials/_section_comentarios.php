<div id="div-comentario-lista-container">
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