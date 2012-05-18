<div id="curso-cursoscriador-content">
    <header>
        <hgroup>
            <h1>Cursos em que Eu sou o Criador</h1>
            <h3>Aqui é listado todos Cursos que você criou até agora no WeLearn!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if ($haCursos): ?>
        <h4>Exibindo <em><?php echo $totalCursos ?></em> Cursos criados por você.</h4>
        <ul>
            <?php foreach ($listaCursos as $curso): ?>
            <li>
                <?php echo $curso->toHTML(true) ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4>Não há Cursos criados por você no serviço até agora,
            <?php echo anchor('/curso/criar', 'Crie um clicando aqui!') ?></h4>
        <?php endif; ?>
    </div>
</div>