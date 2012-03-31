<div id="aula-listar-content">
    <header>
        <hgroup>
            <h1>Aulas do Módulo <?php echo $modulo->nroOrdem ?></h1>
            <h3>Abaixo você encontrará as aulas pertencentes ao módulo
                "<?php echo $modulo->nome ?>"</h3>
        </hgroup>
        <p>
            Quer criar uma nova aula para este módulo?
            <?php echo anchor('/curso/conteudo/aula/criar/' . $modulo->id, 'Clique aqui!') ?>
        </p>
    </header>
    <div>
    <?php if ($haAulas): ?>
        <p>
            Sinta-se livre para mudar a ordem das aulas da maneira que preferir.
            Para isso, basta clicar e arrastar a aula que quiser para posição
            de sua preferência e então clicar no botão "Salvar ordem das aulas",
            que aparecerá logo acima e abaixo da lista.
        </p>
        <div class="div-aula-gerenciar-posicoes">
            <button class="btn-aula-salvar-posicoes">Salvar ordem das aulas</button>
        </div>
        <ul id="ul-aula-listar-lista" data-id-curso="<?php echo $modulo->id ?>">
            <?php echo $listaAulas ?>
        </ul>
        <div class="div-aula-gerenciar-posicoes">
            <button class="btn-aula-salvar-posicoes">Salvar ordem das aulas</button>
        </div>
    <?php else: ?>
        <h4>
            Nenhuma aula foi criada neste módulo até o momento.
            <?php echo anchor(
                '/curso/conteudo/aula/criar/' . $modulo->id,
                'Crie a primeira!'
            ) ?>
        </h4>
    <?php endif; ?>
    </div>
</div>