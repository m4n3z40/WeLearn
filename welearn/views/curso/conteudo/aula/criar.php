<div id="aula-criar-content">
    <header>
        <hgroup>
            <h1>Criar Aula no Módulo "<?php echo $modulo->nome ?>"</h1>
            <h3>Preencha abaixo os campos com os dados
                necessários para criação da aula.</h3>
        </hgroup>
        <p>
            Aulas são compostas de várias páginas de conteúdos organizadas
            <br>
            Não queria estar aqui?
            <?php echo anchor(
                '/curso/conteudo/aula/listar/' . $modulo->id,
                'Volte para a lista de aulas deste módulo'
            ) ?>
        </p>
    </header>
    <div>
    <?php if ($ultrapassouLimite): ?>
        <h3>O limite máximo de aulas foi atingido neste módulo!</h3>
        <p>Por enquanto não é permitido um módulo conter mais de
           <strong><?php echo $maxAulas ?></strong> aulas. <br>
            <?php echo anchor(
                '/curso/conteudo/aula/listar/' . $modulo->id,
                'Volte para a lista de aulas deste módulo'
            ) ?></p>
    <?php else: ?>
        <?php echo $form ?>
    <?php endif; ?>
    </div>
</div>