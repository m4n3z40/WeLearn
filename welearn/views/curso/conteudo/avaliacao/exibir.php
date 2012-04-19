<div id="avaliacao-exibir-content">
    <header>
        <hgroup>
            <h1>Avaliação do Módulo <?php echo $modulo->nroOrdem ?></h1>
            <h3>Aqui você pode gerenciar a avaliação do módulo
                <em>"<?php echo $modulo->nome ?>"</em></h3>
        </hgroup>
        <p>
            Não queria estar aqui? <?php echo anchor('/curso/conteudo/avaliacao/'
                                                         . $modulo->curso->id,
                                                     'Clique aqui para voltar para index de Avaliações') ?>
        </p>
    </header>
    <div>
        <?php if ($modulo->existeAvaliacao): ?>
        <!--TODO: Implementar exibição de avaliacao. -->
        <?php else: ?>
        <h4>Não há uma avaliação vinculada a este módulo até o momento.
            <?php echo anchor('/curso/conteudo/avaliacao/criar/' . $modulo->id,
                              '<br>Clique aqui para criar uma avaliação para este módulo') ?></h4>
        <?php endif; ?>
    </div>
    <footer>
    </footer>
</div>