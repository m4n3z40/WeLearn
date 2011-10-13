<section id="curso-info-content">
    <div id="curso-info-content-tema">
        <h4>Tema</h4>
        <div><?php echo $curso->tema ?></div>
    </div>
    <hr class="curso-info-content-separator" />
    <?php if ( $curso->descricao ): ?>
    <div>
        <h4>Descricao</h4>
        <div><?php echo $curso->descricao ?></div>
    </div>
    <hr class="curso-info-content-separator" />
    <?php endif ?>
    <?php if ( $curso->objetivos ): ?>
    <div>
        <h4>Objetivos</h4>
        <div><?php echo $curso->objetivos ?></div>
    </div>
    <hr class="curso-info-content-separator" />
    <?php endif ?>
    <?php if ( $curso->conteudoProposto ): ?>
    <div>
        <h4>Conteúdo Proposto</h4>
        <div><?php echo $curso->conteudoProposto ?></div>
    </div>
    <hr class="curso-info-content-separator" />
    <?php endif ?>
    <div>
        <h4>Área de Segmento</h4>
        <div><?php echo $curso->segmento->area->descricao ?></div>
    </div>
    <hr class="curso-info-content-separator" />
    <div>
        <h4>Segmento do Curso</h4>

        <div><?php echo $curso->segmento->descricao ?></div>
    </div>
    <hr class="curso-info-content-separator" />
</section>
