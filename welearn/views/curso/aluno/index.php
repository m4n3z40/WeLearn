<div id="aluno-index-content">
    <header>
        <hgroup>
            <h1>Alunos do Curso</h1>
            <h3>Aqui você poderá ficar atualizado sobre quem são os alunos do curso</h3>
        </hgroup>
        <p>Verifique no menu ao lado suas opções.</p>
    </header>
    <div>
        <div>
            <h3>Últimas Requisições de Inscrição</h3>
            <p>Os usuários abaixo estão querendo sua permissão para participar do curso.</p>
            <?php if ($haInscricoes): ?>
            <ul id="ul-lista-requisicoes">
                <?php echo $ultimasRequisicoes ?>
            </ul>
            <?php echo anchor('/curso/aluno/requisicoes/' . $idCurso, 'Exibir todas as requisições') ?>
            <?php else: ?>
            <h4>Não há nenhuma requisição aguardando aprovação.</h4>
            <?php endif; ?>
        </div>
    </div>
</div>