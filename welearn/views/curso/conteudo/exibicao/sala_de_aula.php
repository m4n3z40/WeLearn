<div id="exibicao-conteudo-saladeaula">
    <div id="main-section-container">
        <section id="section-container-iframe">
            <iframe src="<?php echo site_url('/curso/conteudo/exibicao/exibir') ?>"
                    frameborder="0"
                    id="iframe-exibicao-pagina"></iframe>
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