<section id="home-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <header>
        <figure>
            <figcaption>
                <?php echo anchor('/usuario/' . $usuario->id, $usuario->toHTML('imagem_grande')) ?>
            </figcaption>
        </figure>
    </header>
    <hr>
    <nav>
        <h3>Principal</h3>
        <ul>
            <li><?php echo anchor('/home', 'Home') ?></li>
            <li><?php echo anchor('/usuario/amigos/listar/', 'Amigos') ?></li>
            <li><?php echo anchor('/curso', 'Cursos') ?></li>
            <li><?php echo anchor('/usuario/mensagem', 'Mensagens') ?></li>
            <li><?php echo anchor('/notificacao', 'Notificações') ?></li>
            <li><?php echo anchor('/usuario/configuracao', 'Configurações') ?></li>
            <li><?php echo anchor('/administracao','Adminstração')?></li>
            <li><?php echo anchor('/curso/meus_certificados','Certificados')?></li>
        </ul>
    </nav>
</section>