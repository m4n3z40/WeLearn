<div id="review-index-content">
    <header>
        <hgroup>
            <h1>Reputação do Curso</h1>
            <h3>Aqui você encontrará as opniões dos alunos do curso</h3>
        </hgroup>
        <p>As opniões dos alunos influenciam na reputação e popularidade do curso,
           onde os cursos com as melhores reputações terão prioridade nas buscas
           dos usuários do serviço.</p>
        <?php echo gerar_menu_autorizado(
            array(
                array(
                    'uri' => '/curso/review/enviar/' . $idCurso,
                    'texto' => 'Clique Aqui!',
                    'acao' => 'review/enviar',
                    'papel' => $papelUsuarioAtual
                )
            ),
            array('<p>Deseja avaliar o curso? ','</p>')
        ) ?>
    </header>
    <div>
        <h3>Até agora, o curso foi avaliado <em><?php echo $totalReviews ?> vez(es)</em></h3>
        <table id="tbl-reputacao-curso">
            <tr>
                <th>Média da Qualidade do Curso</th>
                <th>Média da Dificuldade do Curso</th>
            </tr>
            <tr>
                <td><?php echo $mediaQualidade ?></td>
                <td><?php echo $mediaDificuldade ?></td>
            </tr>
        </table>
        <hr>
        <h3>Ultimas Avaliações:</h3>
        <?php if ($totalReviews > 0): ?>
        <?php echo anchor(
            '/curso/review/listar/' . $idCurso,
            'Exibir Todas as Avaliações',
            'class="button big-button"'
        ) ?>
        <ul id="ul-lista-reviews">
            <?php echo $listaUltimasReviews; ?>
        </ul>
        <?php else: ?>
        <h4>Nenhuma Avaliação foi enviada recentemente.</h4>
        <?php endif; ?>
    </div>
</div>