<div id="conteudo-exibicao-index-content">
    <header>
        <hgroup>
            <h1>Sala de Aula</h1>
            <h3>Aqui é onde você visualiza todo o conteudo existente neste curso!</h3>
        </hgroup>
        <p>Clique abaixo para iniciar a visualização da ultima aula em que parou!</p>
    </header>
    <div>
    <?php if ( !$conteudoAberto ): ?>
        <h3>O conteúdo deste curso está bloqueado para os alunos, notifique os gerenciadores.</h3>
    <?php elseif ( $moduloAtual ): ?>
        <div id="div-visualizacao-conteudo-janela-aula"
             style="display: none;"
             title="Sala de Aula :: <?php echo $moduloAtual->curso->nome ?>">
            <?php echo $htmlJanelaSalaDeAula ?>
        </div>
        <?php if ( ! $paginaAtual ): ?>
            <h3>Este curso foi interrompido por falta de conteúdo, e ainda não pode continuar :(</h3>
            <h4>Contate os gerenciadores e informe-os!</h4>
        <?php endif; ?>
        <h3><?php echo $iniciouCurso ? 'Continuar Curso:' : 'Iniciar Curso:' ?></h3>
        <button id="btn-iniciar-visualizacao-conteudo">
            <?php echo $iniciouCurso ? 'Continuar de onde parei!' : 'Começar meu aprendizado!' ?>
        </button>
        <hr>
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
        <?php endif; ?>
        <hr>
        <h3>Em que parte do curso eu estou?</h3>
        <ul>
            <li>
                <span>Você está no módulo <?php echo $moduloAtual->nroOrdem ?>: <em>"<?php echo $moduloAtual->nome ?>"</em></span>
            </li>
            <?php if ( $aulaAtual ): ?>
                <li>
                <span>Na aula <?php echo $aulaAtual->nroOrdem ?>: <em>"<?php echo $aulaAtual->nome ?>"</em>;</span>
                </li>
            <?php else: ?>
            <li><span>Você não está assistindo nenhuma aula no momento;</span></li>
            <?php endif; ?>
            <?php if ($paginaAtual): ?>
            <li>
                <span>Visualizando a página <?php echo $paginaAtual->nroOrdem ?>: <em>"<?php echo $paginaAtual->nome ?>"</em></span>
            </li>
            <?php else: ?>
            <li><span>Você não está em nenhuma página no momento.</span></li>
            <?php endif; ?>
        </ul>
    <?php else: ?>
        <h3>Até este momento, o curso não possui conteúdo para os alunos :(</h3>
    <?php endif; ?>
    </div>
</div>