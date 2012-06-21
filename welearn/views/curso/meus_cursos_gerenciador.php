<div id="curso-cursosgerenciador-content">
    <header>
        <hgroup>
            <h1>Cursos em que sou Gerenciador</h1>
            <h3>Aqui é listado todos os Cursos em que você colabora como Gerenciador.</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if ($haCursos): ?>
        <h4>Exibindo <em><?php echo $totalCursos ?></em> Curso(s) em que você colabora no gerenciamento.</h4>
        <ul class="ul-grid-home-cursos">
            <?php foreach ($listaCursos as $curso): ?>
            <li>
                <?php echo $curso->toHTML(true) ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4>Você ainda não é Gerenciador em nenhum curso do WeLearn :( </h4>
        <?php endif; ?>
    </div>
</div>