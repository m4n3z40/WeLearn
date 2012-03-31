<div id="enquete-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar dados da enquete</h1>
            <h3>Altere os dados necessários e depois clique em salvar</h3>
        </hgroup>
        <p>
            Não é aqui que queria estar? <?php echo anchor('/curso/enquete/' . $enquete->curso->id, 'Volte para lista de enquetes.') ?>
        </p>
    </header>
    <div>
        <?php echo $formAlterar ?>
    </div>
</div>