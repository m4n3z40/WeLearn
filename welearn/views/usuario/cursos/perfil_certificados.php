<div id="curso-meuscertificados-content">
    <header>
        <hgroup>
            <h1>Certificados de <?echo $usuarioPerfil->nome?></h1>
            <h3>Aqui é listado todos Certificados que <?echo $usuarioPerfil->nome?> adquiriu até agora no WeLearn!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if ($haCertificados): ?>
        <h4>Exibindo <em><?php echo $totalCertificados ?></em> Certificado(s).</h4>
        <ul class="ul-grid-home-cursos">
            <?php foreach ($listaCertificados as $certificado): ?>
            <li>
                <figure>
                    <a href="#" class="a-exibir-certificado" data-id="<?php echo $certificado->id ?>">
                        <img src="<?php echo $certificado->urlSmall ?>"
                             alt="<?php echo $certificado->descricao ?>"
                             title="<?php echo $certificado->descricao ?>">
                    </a>
                    <figcaption>
                        Certificado do curso <?php echo anchor('/curso/' . $certificado->curso->id, $certificado->curso->nome) ?>
                    </figcaption>
                </figure>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4><?echo $usuarioPerfil->nome?> ainda não recebeu Certificado em nenhum Curso do WeLearn :(</h4>
        <?php endif; ?>
    </div>
</div>