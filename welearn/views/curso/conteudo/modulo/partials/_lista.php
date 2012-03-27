<?php foreach ($listaModulos as $modulo): ?>
<li>
    <header>
        <h3><?php echo $modulo->nome ?></h3>
        <span>Ordem: <em><?php echo $modulo->nroOrdem ?></em></span>
    </header>
    <div>
        <div>
            <h4>Descrição:</h4>
            <p><?php echo nl2br($modulo->descricao) ?></p>
        </div>
        <div>
            <h4>Objetivos:</h4>
            <p><?php echo nl2br($modulo->objetivos) ?></p>
        </div>
    </div>
</li>
<?php endforeach; ?>