<div>
    <h3>Informações sobre a etapa atual</h3>
    <article id="art-modulo-infoetapa-saladeaula">
        <h4>Módulo <em><?php echo $modulo->nroOrdem ?></em>: <em>"<?php echo $modulo->nome ?>"</em></h4>
        <dl>
            <dt><em>Descrição do Módulo:</em></dt>
            <dd><?php echo nl2br($modulo->descricao) ?></dd>
            <dt><em>Objetivos do Módulo:</em></dt>
            <dd><?php echo nl2br($modulo->objetivos) ?></dd>
        </dl>
    </article>
    <?php if ($aula): ?>
        <article id="art-aula-infoetapa-saladeaula">
            <h4>Aula <em><?php echo $aula->nroOrdem ?></em>: <em>"<?php echo $aula->nome ?>"</em></h4>
            <dl>
                <dt><em>Descrição da Aula:</em></dt>
                <dd><?php echo nl2br($aula->descricao) ?></dd>
            </dl>
        </article>
    <?php endif; ?>
    <?php if ($pagina): ?>
        <article id="art-pagina-infoetapa-saladeaula">
            <h4>Página <em><?php echo $aula->nroOrdem ?></em>: <em>"<?php echo $pagina->nome ?>"</em></h4>
        </article>
    <?php endif; ?>
</div>
<hr>
<div>
    <h3>Navegação</h3>
    <dl>
        <dt><label for="slt-modulos">Modulo Atual: </label></dt>
        <dd><?php echo $selectModulos ?></dd>
        <dt><label for="slt-areas">Aula Atual: </label></dt>
        <dd><?php echo $selectAulas ?></dd>
        <dt><label for="slt-paginas">Página Atual: </label></dt>
        <dd><?php echo $selectPaginas ?></dd>
    </dl>
</div>