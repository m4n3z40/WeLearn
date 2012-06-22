<div id="curso-cursoscriador-content">
    <header>
        <hgroup>
            <h1>Cursos em que <?echo $usuarioPerfil->nome?> é o Criador</h1>
            <h3>Aqui é listado todos Cursos criados por <?echo $usuarioPerfil->nome?> até agora no WeLearn!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if ($haCursos): ?>
        <h4>Exibindo <em><?php echo $totalCursos ?></em> Cursos criados por <?echo $usuarioPerfil->nome?>.</h4>
        <ul class="ul-grid-home-cursos">
            <?php foreach ($listaCursos as $curso): ?>
            <li>
                <?php echo $curso->toHTML(true) ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4>Não há Cursos criados por <?echo $usuarioPerfil->nome?> no serviço até agora,</h4>
        <?php endif; ?>
    </div>
</div>