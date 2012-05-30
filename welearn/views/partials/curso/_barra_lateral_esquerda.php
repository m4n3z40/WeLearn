<section id="curso-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <nav>
        <h3>Principal</h3>
        <ul>
            <li><?php echo anchor('/curso/' . $idCurso, 'Home') ?></li>
            <li><?php echo anchor('/curso/conteudo/exibicao/' . $idCurso, 'Sala de Aula') ?></li>
            <li><?php echo anchor('/curso/aluno/' . $idCurso, 'Alunos') ?></li>
            <li><?php echo anchor('/curso/enquete/' . $idCurso, 'Enquetes') ?></li>
            <li><?php echo anchor('/curso/forum/' . $idCurso, 'Fóruns') ?></li>
            <li><?php echo anchor('/curso/gerenciador/' . $idCurso, 'Gerenciadores') ?></li>
            <li><?php echo anchor('/curso/conteudo/' . $idCurso, 'Gerenciamento de Conteúdo') ?></li>
            <li><?php echo anchor('/curso/certificado/' . $idCurso, 'Gerenciamento de Certificados') ?></li>
            <li><?php echo anchor('/curso/review/' . $idCurso, 'Reputação do Curso') ?></li>
            <li><?php echo anchor('/curso/configurar/' . $idCurso, 'Configurações do Curso') ?></li>
        </ul>
    </nav>
    <hr>
    <div id="curso-left-bar-contexto">
        <?php if (! empty($menuContexto)): ?>
        <nav id="curso-left-bar-contexto-menu">
            <?php echo $menuContexto; ?>
        </nav>
        <hr>
        <?php endif ?>
        <?php if (! empty($widgetsContexto)): ?>
        <section id="curso-left-bar-contexto-widgets">
            <?php foreach ($widgetsContexto as $widget): ?>
                <?php echo $widget ?>
                <hr class="curso-widget-separator">
            <?php endforeach ?>
        </section>
        <?php endif ?>
    </div>
</section>