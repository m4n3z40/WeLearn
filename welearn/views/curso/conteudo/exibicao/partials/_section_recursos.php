<div id="div-recursos-lista-container">
    <h3>Recursos Extras neste Curso</h3>
    <nav>
        <ul>
            <li><a href="#div-recursos-gerais"
                   id="a-exibir-recursos-gerais"
                   data-tipo-recurso="<?php echo WeLearn_Cursos_Recursos_TipoRecurso::GERAL ?>">Recursos Gerais</a></li>
            <li><a href="#div-recursos-restritos"
                   id="a-exibir-recursos-restritos"
                   data-tipo-recurso="<?php echo WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO ?>">Recursos Restritos à esta Aula</a></li>
        </ul>
    </nav>
    <div id="div-recursos-gerais" style="display: none;">
        <h4>Exibindo <em id="em-qtd-recursos-gerais">0</em> de
            <em id="em-total-recursos-gerais">0</em> Recursos Gerais</h4>
        <div id="div-recursos-gerais-lista-listar">
            <h4 style="display: none;" id="h4-msg-sem-recursos-gerais">
                Não há recursos gerais vinculados à este curso.
            </h4>
            <ul style="display: none;" id="ul-lista-recursos-gerais"></ul>
            <footer style="display: none;" id="foo-paginacao-recursos-gerais">
                <h4 id="h4-msg-sem-mais-recursos-gerais">Não há mais recursos gerais a serem
                    exibidos...</h4>
                <a href="#"
                   id="a-paginacao-recursos-gerais"
                   data-tipo-recurso="<?php echo WeLearn_Cursos_Recursos_TipoRecurso::GERAL ?>"
                   daya-proximo="">Mais Recursos Gerais</a>
            </footer>
        </div>
    </div>
    <div id="div-recursos-restritos" style="display: none;">
        <h4>Exibindo <em id="em-qtd-recursos-restritos">0</em> de
            <em id="em-total-recursos-restritos">0</em> Recursos vinculados à aula
            "<em id="em-nome-aula-recursos-restritos">Aula</em>"</h4>
        <div id="div-recursos-restritos-lista-listar">
            <h4 style="display: none;" id="h4-msg-sem-recursos-restritos">
                Não há recursos restritos vinculados à esta aula.
            </h4>
            <ul style="display: none;" id="ul-lista-recursos-restritos"></ul>
            <footer style="display: none;" id="foo-paginacao-recursos-restritos">
                <h4 id="h4-msg-sem-mais-recursos-restritos">Não há mais recursos restritos a serem
                    exibidos...</h4>
                <a href="#"
                   id="a-paginacao-recursos-restritos"
                   data-tipo-recurso="<?php echo WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO ?>"
                   data-proximo="">Mais Recursos Restritos</a>
            </footer>
        </div>
    </div>
</div>