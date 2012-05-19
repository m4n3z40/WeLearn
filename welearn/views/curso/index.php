<div id="curso-index-content">
    <header>
        <hgroup>
            <h1>Cursos do WeLearn</h1>
            <h3>Dê uma olhada no menu à direita e veja o que você pode fazer por aqui!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <h3>Cursos recomedados a você!</h3>
        <?php if ($haRecomendados): ?>
        <ul id="ul-lista-cursos-recomendados">
            <?php foreach ($listaRecomendados as $curso): ?>
            <li>
                <?php echo $curso->toHTML(true) ?>
                <ul>
                    <li><strong><?php echo $curso->totalReviews ?></strong> Reviews</li>
                    <li><strong>Qualidade: </strong><?php echo number_format($curso->mediaQualidade, 1, ',', '.') ?></li>
                    <li><strong>Dificuldade: </strong><?php echo number_format($curso->mediaQualidade, 1, ',', '.') ?></li>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4>Não há cursos recomendados a você no momento.</h4>
        <?php endif; ?>
    </div>
</div>