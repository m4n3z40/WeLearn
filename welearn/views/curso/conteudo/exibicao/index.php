<div id="conteudo-exibicao-index-content">
    <header>
        <hgroup>
            <h1>Frequentar Aulas</h1>
            <h3>Aqui é onde você visualiza todo o conteudo existente neste curso!</h3>
        </hgroup>
        <p>Clique abaixo para iniciar a visualização da ultima aula em que parou!</p>
    </header>
    <div>
        <?php if ($iniciouCurso): ?>
        <hgroup>
            <h3>Você ainda não frequentou nenhuma aula deste curso.</h3>
            <h4>O que está esperando? Clique no botão abaixo e inicie seu aprendizado agora!</h4>
        </hgroup>
        <?php endif; ?>
        <ul>
            <li>
                <span>Você está no módulo <?php echo $moduloAtual->nroOrdem ?>: <em><?php echo $moduloAtual->nome ?></em></span>
            </li>
            <li>
                <span>Na aula <?php echo $aulaAtual->nroOrdem ?>: <em><?php echo $aulaAtual->nome ?></em></span>
            </li>
            <li><span>Visualizando a página <?php echo $paginaAtual->nroOrdem ?>: <em><?php echo $paginaAtual->nome ?></em></span></li>
        </ul>
        <hr>
        <h3><?php echo $iniciouCurso ? 'Continuar Curso:' : 'Iniciar Curso:' ?></h3>
        <button id="btn-iniciar-visualizacao-conteudo">
            Abrir Página "<?php echo $paginaAtual->nome ?>" da Aula "<?php echo $aulaAtual->nome ?>"
        </button>
        <hr>
        <h3>Seu progresso atual neste curso:</h3>
        <div id="div-progresso-curso" style="width: <?php echo $progressoNoCurso ?>%;">
            <span><?php echo $progressoNoCurso ?>% do Curso Concluído.</span>
        </div>
    </div>
</div>