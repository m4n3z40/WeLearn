<div>
    <h3>PARABÉNS! Você finalizou este curso!</h3>
    <h4>Você passou por todos os conteúdos e avaliações necessárias
        para que o curso fosse concluído!</h4>
    <p>Esperamos que você tenha assimilado bastante conhecimento, e
        que passe este conhecimento adiante, como os gerenciadores deste curso fizeram.</p>
    <p>Abaixo verá o certificado que ganhou, a partir de agora ele
        se encontrará na lista de certificados, na área de cursos da sua Home.</p>
    <?php if ($certificado): ?>
    <figure>
        <img src="<?php echo $certificado->urlBig ?>" alt="Certificado do Curso">
        <figcaption>
            <h4>Uma palavra dos Gerenciadores deste Curso:</h4>
            <blockquote>
                <?php echo nl2br($certificado->descricao) ?>
            </blockquote>
        </figcaption>
    </figure>
    <?php else: ?>
    <h4>Seu certificado foi removido pelos gerenciadores deste curso.</h4>
    <?php endif; ?>
</div>