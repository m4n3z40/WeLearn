<div id="curso-cursosaluno-content">
    <header>
        <hgroup>
            <h1>Cursos em que <?echo $usuarioPerfil->nome?> é Aluno</h1>
            <h3>Aqui está sendo listado todos os Cursos em que <?echo $usuarioPerfil->nome?> é Aluno e ainda não concluiu.</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if ($haCursos): ?>
        <h4>Exibindo <em><?php echo $totalCursos ?></em> Cursos em que <?echo $usuarioPerfil->nome?> é aluno.</h4>
        <ul  class="ul-grid-home-cursos">
            <?php foreach ($listaCursos as $curso): ?>
            <li>
                <?php echo $curso->toHTML(true) ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4><?echo $usuarioPerfil->nome?> ainda não é aluno em nenhum curso do WeLearn :(</h4>
        <?php endif; ?>
    </div>
</div>