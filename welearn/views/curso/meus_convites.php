<div id="curso-meusconvites-content">
    <header>
        <hgroup>
            <h1>Meus Convites para Cursos</h1>
            <h3>Aqui é listado todos Convites de Cursos que se encontram pendentes.</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if ($haCursos): ?>
        <h4>Exibindo <em id="em-total-convite-gerenciamento"><?php echo $totalCursos ?></em> Convite(s) para o gerenciamento de Curso.</h4>
        <ul class="ul-grid-home-cursos">
            <?php foreach ($listaCursos as $curso): ?>
            <li>
                <?php echo $curso->toHTML(true) ?>
                <ul>
                    <li><?php echo anchor(
                        '/curso/gerenciador/aceitar_convite/' . $curso->id,
                        'Aceitar',
                        'class="a-aceitar-convite-gerenciamento"'
                    ) ?>
                    </li><li><?php echo anchor(
                        '/curso/gerenciador/recusar_convite/' . $curso->id,
                        'Recusar',
                        'class="a-recusar-convite-gerenciamento"'
                    ) ?></li>

                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4>Você não tem nenhum convite para o gerenciamento de um curso.</h4>
        <?php endif; ?>
    </div>
</div>