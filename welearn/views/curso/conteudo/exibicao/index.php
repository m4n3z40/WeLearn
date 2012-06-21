<div id="conteudo-exibicao-index-content">
    <header>
        <hgroup>
            <h1>Sala de Aula</h1>
            <h3>Aqui é onde você visualiza todo o conteudo existente neste curso!</h3>
        </hgroup>
        <p>Clique abaixo para iniciar a visualização da ultima aula em que parou!</p>
    </header>
    <div>
    <?php if ( $conteudoBloqueado ): ?>
        <h3>O conteúdo deste curso está bloqueado para os alunos, notifique os gerenciadores.</h3>
    <?php elseif ( $moduloAtual ): ?>
        <div id="div-visualizacao-conteudo-janela-aula"
             style="display: none;"
             title="Sala de Aula :: <?php echo $moduloAtual->curso->nome ?>">
            <?php echo $htmlJanelaSalaDeAula ?>
        </div>
        <?php if ( ! ($paginaAtual || $avaliacaoAtual) ): ?>
            <h3>Este curso foi interrompido por falta de conteúdo, e ainda não pode continuar :(</h3>
            <h4>Contate os gerenciadores e informe-os!</h4>
        <?php endif; ?>
        <div>
        <?php if ( ! $iniciouCurso ): ?>
            <hgroup>
                <h3>Você ainda não frequentou nenhuma aula deste curso.</h3>
                <h4>O que está esperando? Clique no botão acima e inicie seu aprendizado agora!</h4>
            </hgroup>
        <?php else: ?>
            <h3>Seu progresso atual neste curso:</h3>
            <div id="div-progresso-curso" style="width: <?php echo $progressoNoCurso ?>%;">
                <span><?php echo $progressoNoCurso ?>% do Curso Concluído.</span>
            </div>
            <div>
                <ul>
                    <li><strong>Data de Ingresso:</strong> <?php echo date('d/m/Y', $dataInscricao) ?></li>
                    <li><strong>Último Acesso:</strong> <?php echo date('d/m/Y à\s H:i:s', $dataUltimoAcesso) ?></li>
                    <li><strong>Tempo de Curso:</strong> <?php echo round($frequenciaTotal, 1) ?> h</li>
                    <?php if ($finalizouCurso): ?>
                    <li><strong>CR do Curso:</strong> <?php echo number_format($crFinal, 1, ',', '.') ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if ( $finalizouCurso ): ?>
                <?php echo $htmlCertificado ?>
            <?php endif; ?>
        <?php endif; ?>
        </div>
        <div>
            <h3>Em que parte do curso eu estou?</h3>
            <ul>
                <li>
                    <span>Você está no módulo <?php echo $moduloAtual->nroOrdem ?>: <em>"<?php echo $moduloAtual->nome ?>"</em>;</span>
                </li>
                <?php if ( $avaliacaoAtual ): ?>
                <li>
                    <span>Você está à fazer a avaliação <em>"<?php echo $avaliacaoAtual->nome ?>"</em> do Módulo <em>"<?php echo $moduloAtual->nroOrdem ?>"</em>;</span><br>
                    <?php echo anchor(
                        '/curso/conteudo/aplicacao_avaliacao/' . $moduloAtual->curso->id,
                        'Ir para a Avaliação'
                    ) ?>
                </li>
                <?php else: ?>
                <li><span>Você não tem nenhuma avaliação pendente no momento;</span></li>
                <?php endif; ?>
                <?php if ( $aulaAtual ): ?>
                <li>
                <span>Na aula <?php echo $aulaAtual->nroOrdem ?>: <em>"<?php echo $aulaAtual->nome ?>"</em>;</span>
                </li>
                <?php else: ?>
                <li><span>Você não está assistindo nenhuma aula no momento;</span></li>
                <?php endif; ?>
                <?php if ($paginaAtual): ?>
                <li>
                    <span>Visualizando a página <?php echo $paginaAtual->nroOrdem ?>: <em>"<?php echo $paginaAtual->nome ?>"</em>.</span>
                </li>
                <?php else: ?>
                <li><span>Você não está em nenhuma página no momento.</span></li>
                <?php endif; ?>
            </ul>
        </div>
        <div>
            <h3><?php echo $iniciouCurso ? $finalizouCurso ? 'Curso Concluido!' : 'Continuar Curso:' : 'Iniciar Curso:' ?></h3>
            <button id="btn-iniciar-visualizacao-conteudo" class="big-button">
                <?php echo $iniciouCurso ? $finalizouCurso ? 'Continuar visualizando as Aulas' : 'Continuar de onde parei!' : 'Começar meu Aprendizado!' ?>
            </button>
        </div>
    <?php else: ?>
        <h3>Até este momento, o curso não possui conteúdo para os alunos :(</h3>
    <?php endif; ?>
    </div>
</div>