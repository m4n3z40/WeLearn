<?php foreach ($listaCertificados as $certificado): ?>
<li>
    <article>
        <?php echo $certificado; ?>
        <nav>
            <ul>
                <li><?php echo anchor(
                    '/curso/certificado/exibir/' . $certificado->id,
                    'Visualizar',
                    'class="a-exibir-certificado"'
                ) ?></li>
                <li><?php echo anchor(
                    '/curso/certificado/alterar/' . $certificado->id,
                    'Alterar',
                    'class="a-alterar-certificado"'
                ) ?></li>
                <li><?php echo anchor(
                    '/curso/certificado/remover/' . $certificado->id,
                    'Remover',
                    'class="a-remover-certificado"'
                ) ?></li>
            </ul>
        </nav>
    </article>
</li>
<?php endforeach; ?>