<div id="modulo-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar Módulo "<?php echo $nomeModulo ?>"</h1>
            <h3>Altere os dados nos campos abaixo e depois clique em "salvar"
                para atualizar os dados deste módulo.</h3>
        </hgroup>
        <p>
            Não queria estar aqui?
            <?php echo anchor('/curso/conteudo/modulo/' . $idCurso,
                              'Volte para a lista de módulos') ?>
        </p>
    </header>
    <div>
        <?php echo $form ?>
    </div>
</div>