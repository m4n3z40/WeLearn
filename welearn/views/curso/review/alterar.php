<div id="review-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar Avaliação de Curso</h1>
            <h3>Altere os dados da sua opinião sobre o curso <em>"<?php echo $nomeCurso ?>"</em></h3>
        </hgroup>
        <p>As opniões dos alunos influenciam na reputação e popularidade do curso,
           onde os cursos com as melhores reputações terão prioridade nas buscas
           dos usuários do serviço.</p>
        <p>Não queria estar aqui? <?php echo anchor('/curso/review/listar/' . $idCurso,
                                                    'Retorne à listagem de avaliações do curso') ?></p>
    </header>
    <div>
        <?php echo $form ?>
    </div>
</div>