<div>
    <h3>Informações sobre a etapa atual</h3>
    <article>
        <h4>Módulo <?php echo $modulo->nroOrdem ?>: <em>"<?php echo $modulo->nome ?>"</em></h4>
        <dl>
            <dt><em>Descrição do Módulo:</em></dt>
            <dd><?php echo nl2br($modulo->descricao) ?></dd>
            <dt><em>Objetivos do Módulo:</em></dt>
            <dd><?php echo nl2br($modulo->objetivos) ?></dd>
        </dl>
    </article>
    <article>
        <h4>Aula <?php echo $aula->nroOrdem ?>: <em>"<?php echo $aula->nome ?>"</em></h4>
        <dl>
            <dt><em>Descrição da Aula:</em></dt>
            <dd><?php echo nl2br($aula->descricao) ?></dd>
        </dl>
    </article>
    <article>
        <h4>Página <?php echo $aula->nroOrdem ?>: <em>"<?php echo $pagina->nome ?>"</em></h4>
    </article>
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