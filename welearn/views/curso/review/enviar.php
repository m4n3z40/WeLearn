<div id="review-enviar-content">
    <header>
        <hgroup>
            <h1>Enviar Avaliação de Curso</h1>
            <h3>Envie sua opinião sobre o curso <em>"<?php echo $nomeCurso ?>"</em></h3>
        </hgroup>
        <p>As opniões dos alunos influenciam na reputação e popularidade do curso,
           onde os cursos com as melhores reputações terão prioridade nas buscas
           dos usuários do serviço.</p>
    </header>
    <div>
    <?php if ( $usuarioJaEnviou ): ?>
        <h3>Vocẽ já enviou a sua análise sobre este curso.
            Para visualizá-la, <?php echo anchor('/curso/review/listar/' . $idCurso, 'clique aqui!') ?></h3>
    <?php else: ?>
        <?php echo $form ?>
    <?php endif; ?>
    </div>
</div>