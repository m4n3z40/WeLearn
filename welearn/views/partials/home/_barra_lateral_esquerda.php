<section id="home-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <header>
        <figure>
            <?php if ($usuario->imagem): ?>
            <img src="<?php echo $usuario->imagem->url ?>" alt="Perfil de <?php echo $usuario->nome ?>">
            <?php endif; ?>
            <figcaption>
                <?php echo anchor('/usuario/' . $usuario->id, $usuario->nome) ?>
            </figcaption>
        </figure>
    </header>
    <hr>
    <nav>
        <h3>Principal</h3>
        <ul>
            <li><?php echo anchor('/home/', 'Home') ?></li>
            <li><?php echo anchor('/feed', 'Feeds') ?></li>
            <li><?php echo anchor('/amizade', 'Amigos') ?></li>
            <li><?php echo anchor('/curso', 'Cursos') ?></li>
            <li><?php echo anchor('/mensagem', 'Mensagens') ?></li>
            <li><?php echo anchor('/notificacao', 'Notificações') ?></li>
            <li><?php echo anchor('/configuracao', 'Configurações') ?></li>
        </ul>
    </nav>
</section>