<section id="curso-right-bar" class="inner-sidebar-container inner-sidebar-container-right">
    <header>
        <figure>
            <img src="<?php echo $imagemUrl ?>" alt="<?php echo $descricao ?>" />
            <figcaption><?php echo $nome ?></figcaption>
        </figure>
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