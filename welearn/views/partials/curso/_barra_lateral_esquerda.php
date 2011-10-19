<section id="curso-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <nav>
        <h3>Principal</h3>
        <ul>
            <li><?php echo anchor('curso/' . $idCurso, 'Home') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/conteudo', 'Gerenciamento de conteúdo') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/aluno', 'Alunos') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/enquete', 'Enquetes') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/forum', 'Fóruns') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/gerenciador', 'Gerenciadores') ?></li>
            <li><?php echo anchor('curso/' . $idCurso . '/configurar', 'Configurações do curso') ?></li>
        </ul>
    </nav>
</section>