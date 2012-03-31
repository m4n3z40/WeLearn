<div id="modulo-criar-content">
    <header>
        <hgroup>
            <h1>Criar um Módulo de Curso</h1>
            <h3>Preencha abaixo os campos com os dados
                necessários para criação do módulo.</h3>
        </hgroup>
        <p>
            Módulos servem para organizar aulas. Um módulo pode conter várias aulas.
            <br>
            Não queria estar aqui?
            <?php echo anchor('/curso/conteudo/modulo/' . $idCurso,
                              'Volte para a lista de módulos') ?>
        </p>
    </header>
    <div>
    <?php if ($ultrapassouLimite): ?>
        <h3>O limite máximo de módulos foi atingido neste curso!</h3>
        <p>Por enquanto não é permitido um curso conter mais de
           <strong><?php echo $maxModulos ?></strong> módulos. <br>
            <?php echo anchor('/curso/conteudo/modulo/' . $idCurso,
                              'Volte para a lista de módulos') ?></p>
    <?php else: ?>
        <?php echo $form ?>
    <?php endif; ?>
    </div>
</div>