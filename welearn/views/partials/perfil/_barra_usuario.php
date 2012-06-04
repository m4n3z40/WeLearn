<div id="user-bar">
    <ul>
        <li>
            <section id="logo-section">
                <h3 class="welearn-logo" title="WeLearn - It\'s not about you anymore."><?php echo anchor('/home', 'WeLearn') ?></h3>
            </section>
        </li>
        <li>
            <section id="search-section">
                <?php echo form_open('usuario/busca/buscar')?>
                <input type="text" name="txt-search" id="txt-search"/>
                <?php echo form_submit('enviar','procurar', 'id="btn-submit-search"')?>
                <?php echo form_close();?>
            </section>
        </li>
    </ul>
    <ul>
        <li>
            <section id="logout-section">
                <a href="#" class="logoutButton button">Sair</a>
            </section>
        </li>
        <li>
            <section id="user-section">
                <?php echo $usuario->toHTML('imagem_mini_link_home') ?>
            </section>
        </li>
    </ul>
</div>