<div id="aula-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar Aula "<?php echo $nomeAula ?>"</h1>
            <h3>Altere os campos abaixo e depois clique em "Salvar"
                para atualizar os dados da aula.</h3>
        </hgroup>
        <p>
            Não queria estar aqui?
            <?php echo anchor(
                '/curso/conteudo/aula/listar/' . $idModulo,
                'Volte para a lista de aulas deste módulo'
            ) ?>
    </header>
    <div>
        <?php echo $form ?>
    </div>
</div>