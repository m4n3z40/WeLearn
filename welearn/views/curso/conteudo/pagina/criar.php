<div id="pagina-criar-content">
    <header>
        <hgroup>
            <h1>Adicionar Página na Aula "<?php echo $aula->nome ?>"</h1>
            <h3>Preencha abaixo os campos com os dados
                necessários para criação da Página.</h3>
        </hgroup>
    </header>
    <div>
    <?php if ($ultrapassouLimite): ?>
        <h3>O limite máximo de páginas foi atingido nesta aula!</h3>
        <p>Por enquanto não é permitido uma aula conter mais de
           <strong><?php echo $maxPaginas ?></strong> páginas.</p>
    <?php else: ?>
        <?php echo $form ?>
    <?php endif; ?>
    </div>
</div>