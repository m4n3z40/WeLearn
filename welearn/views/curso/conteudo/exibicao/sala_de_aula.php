<div id="exibicao-conteudo-saladeaula"
     data-tipo-conteudo="<?php echo $tipoConteudo ?>"
     data-id-curso="<?php echo $idCurso ?>"
     data-id-modulo="<?php echo $idModulo ?>"
     data-id-aula="<?php echo $idAula ?>"
     data-id-pagina="<?php echo $idPagina ?>"
     data-id-avaliacao="<?php echo $idAvaliacao ?>">
    <div id="main-section-container">
        <section id="section-container-iframe">
            <iframe src="<?php echo $srcIframeConteudo ?>"
                    frameborder="0"
                    id="iframe-exibicao-pagina"></iframe>
            <nav>
                <ul>
                    <li><a href="#" id="a-nav-exibicao-inicio-aula">Início da Aula</a></li>
                    <li><a href="#" id="a-nav-exibicao-pagina-anterior">Página Anterior</a></li>
                    <li><a href="#" id="a-nav-exibicao-proxima-pagina">Proxima Página</a></li>
                </ul>
            </nav>
        </section>
        <section id="section-container-anotacao">
            <?php echo $htmlSectionAnotacao ?>
        </section>
        <section id="section-container-comentarios">
            <?php echo $htmlSectionComentarios ?>
        </section>
    </div>
    <aside id="aside-widgets-container">
        <section id="section-widget-infoetapa">
            <?php echo $htmlSectionInfoEtapa ?>
        </section>
        <section id="section-widget-recursos">
            <?php echo $htmlSectionRecursos ?>
        </section>
    </aside>
</div>