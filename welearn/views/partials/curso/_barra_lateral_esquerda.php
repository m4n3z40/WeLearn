<section id="curso-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
    <nav>
        <h3>Principal</h3>
        <ul>
            <li><?php echo anchor('curso/' . $idCurso, 'Home') ?></li>
            <li><?php echo anchor('curso/conteudo/' . $idCurso, 'Gerenciamento de conteúdo') ?></li>
            <li><?php echo anchor('curso/aluno/' . $idCurso, 'Alunos') ?></li>
            <li><?php echo anchor('curso/enquete/' . $idCurso, 'Enquetes') ?></li>
            <li><?php echo anchor('curso/forum/' . $idCurso, 'Fóruns') ?></li>
            <li><?php echo anchor('curso/gerenciador/' . $idCurso, 'Gerenciadores') ?></li>
            <li><?php echo anchor('curso/configurar/' . $idCurso, 'Configurações do curso') ?></li>
        </ul>
    </nav>
</section>