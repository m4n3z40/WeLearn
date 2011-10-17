<section id="curso-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <nav>
        <h3>Principal</h3>
        <ul>
            <li><?php echo anchor('curso/' . $idCurso, 'Home') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/conteudo', 'Gerenciamento de conteúdo') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/alunos', 'Alunos') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/enquetes', 'Enquetes') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/foruns', 'Fóruns') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/gerenciadores', 'Gerenciadores') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/configurar', 'Configurações do curso') ?></li>
        </ul>
    </nav>
</section>