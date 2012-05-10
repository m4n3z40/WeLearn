<section id="curso-right-bar" class="inner-sidebar-container inner-sidebar-container-right">
    <header>
        <figure>
            <?php echo anchor(
                '/curso/' . $idCurso,
                "<img src=\"{$imagemUrl}\" alt=\"{$descricao}\" /><figcaption>{$nome}</figcaption>"
            ) ?>
        </figure>
        <div>
        <?php if ( $usuarioNaoVinculado ): ?>
            <?php echo anchor(
                '/curso/inscrever/' . $idCurso,
                'Inscrever-se no Curso',
                'title="Inscrever-se no Curso" id="a-curso-inscreverse" class="button"'
            ) ?>
        <?php elseif( $usuarioPendente ): ?>
            <span data-id-curso="<?php echo $idCurso ?>" title="Vínculo Pendente...">Vínculo com o curso pendente</span>
        <?php else: ?>
            <?php echo anchor(
                '/curso/sair/' . $idCurso,
                'Sair do Curso',
                'title="Sair do Curso" id="a-curso-desvincularse" class="button"'
            ) ?>
        <?php endif; ?>
        </div>
    </header>
    <div id="curso-right-bar-contexto">
        <?php if (! empty($menuContexto)): ?>
        <nav id="curso-right-bar-contexto-menu">
            <?php echo $menuContexto; ?>
        </nav>
        <hr>
        <?php endif ?>
        <?php if (! empty($widgetsContexto)): ?>
        <section id="curso-right-bar-contexto-widgets">
            <?php foreach ($widgetsContexto as $widget): ?>
                <?php echo $widget ?>
                <hr class="curso-widget-separator">
            <?php endforeach ?>
        </section>
        <?php endif ?>
    </div>
</section>