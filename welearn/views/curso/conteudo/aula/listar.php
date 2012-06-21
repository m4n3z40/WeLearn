<div id="aula-listar-content">
    <header>
        <hgroup>
            <h1>Aulas do Módulo <?php echo $modulo->nroOrdem ?></h1>
            <h3>Abaixo você encontrará as aulas pertencentes ao módulo
                "<?php echo $modulo->nome ?>"</h3>
        </hgroup>
        <p>
            <?php if ($modulo->curso->status === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO): ?>
            Não é possível adicionar uma nova aula, o conteúdo do curso está aberto.
            O gerenciamento de conteúdo está limitado.
            <br><br>
            <?php else: ?>
            Quer criar uma nova aula para este módulo?
            <?php echo anchor('/curso/conteudo/aula/criar/' . $modulo->id, 'Clique aqui!') ?>
            <br><br>
            <?php endif; ?>
            Quer gerenciar as aulas de outro módulo?
            <?php echo anchor('#', 'Clique aqui!', array('id' => 'a-aula-alterar-modulo')) ?>
        </p>
        <p>
            Sinta-se livre para mudar a ordem das aulas da maneira que preferir.
            Para isso, basta clicar e arrastar a aula que quiser para posição
            de sua preferência e então clicar no botão "Salvar ordem das aulas",
            que aparecerá logo acima e abaixo da lista.
        </p>
        <div id="div-aula-alterar-modulo" style="display: none;">
            <?php echo $selectModulo ?>
        </div>
    </header>
    <div>
    <?php if ($haAulas): ?>
        <h4 id="p-aula-listar-qtdTotal">
            Exibindo <strong><?php echo $totalAulas ?></strong> Aulas(s) -
            (Máximo permitido: <strong><?php echo AulaDAO::MAX_AULAS ?></strong>)
        </h4>
        <div class="div-aula-gerenciar-posicoes">
            <button>Salvar ordem das aulas</button>
        </div>
        <ul id="ul-aula-listar-lista" data-id-modulo="<?php echo $modulo->id ?>">
            <?php echo $listaAulas ?>
        </ul>
        <div class="div-aula-gerenciar-posicoes">
            <button>Salvar ordem das aulas</button>
        </div>
    <?php else: ?>
        <?php if ($modulo->curso->status === WeLearn_Cursos_StatusCurso::CONTEUDO_ABERTO): ?>
        <h4>
        Não é possível adicionar uma nova aula, o conteúdo do curso está aberto.
        O gerenciamento de conteúdo está limitado.
        </h4>
        <?php else: ?>
        <h4>
            Nenhuma aula foi criada neste módulo até o momento.
            <?php echo anchor(
                '/curso/conteudo/aula/criar/' . $modulo->id,
                'Crie a primeira!'
            ) ?>
        </h4>
        <?php endif; ?>
    <?php endif; ?>
    </div>
</div>