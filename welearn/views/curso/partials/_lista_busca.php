<?php foreach ($listaResultados as $curso): ?>
<li>
    <?php echo $curso->toHTML(true) ?>
    <ul>
        <li><strong><?php echo $curso->totalReviews ?></strong> Reviews</li>
        <li><strong>Qualidade: </strong><?php echo number_format($curso->mediaQualidade, 1, ',', '.') ?></li>
        <li><strong>Dificuldade: </strong><?php echo number_format($curso->mediaQualidade, 1, ',', '.') ?></li>
    </ul>
</li>
<?php endforeach; ?>