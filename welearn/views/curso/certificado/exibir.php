<div id="certificado-exibir-content">
    <header>
        <hrgroup>
            <h1>Exibindo Certificado</h1>
            <h3>Abaixo é mostrado como os alunos visualizarão o certificado ao
                concluirem o curso</h3>
        </hrgroup>
    </header>
    <div>
        <figure>
            <img src="<?php echo $certificado->urlBig ?>" alt="Certificado do Curso">
            <figcaption>
                <blockquote>
                    <?php echo $certificado->descricao ?>
                </blockquote>
            </figcaption>
        </figure>
    </div>
</div>