<div id="review-listar-content">
    <header>
        <hgroup>
            <h1>Reputação do Curso</h1>
            <h3>Aqui você encontrará as opniões dos alunos do curso</h3>
        </hgroup>
        <p>As opniões dos alunos influenciam na reputação e popularidade do curso,
           onde os cursos com as melhores reputações terão prioridade nas buscas
           dos usuários do serviço.</p>
        <p>Não queria estar aqui? <?php echo anchor('/curso/review/' . $idCurso,
                                                    'Retorne à reputação do curso') ?></p>
    </header>
    <div>
    <?php if ($haReviews): ?>
        <h4>Exibindo
            <em id="em-qtd-reviews"><?php echo $qtdReviews ?></em> de
            <em id="em-total-reviews"><?php echo $totalReviews ?></em> avaliação(ões).</h4>
        <ul id="ul-lista-reviews">
            <?php echo $listaReviews; ?>
        </ul>
        <?php if ($haMaisPaginas): ?>
            <a href="#"
               id="a-proxima-pagina"
               data-id-curso="<?php echo $idCurso ?>"
               data-proximo="<?php echo $inicioProxPagina ?>"
               class="button">Avaliações mais antigas...</a>
        <?php else: ?>
            <h4>Não há mais avaliações para serem listadas.</h4>
        <?php endif; ?>
    <?php else: ?>
        <h4>Este curso ainda não recebeu nenhuma avaliação até o momento.</h4>
    <?php endif; ?>
    </div>
</div>