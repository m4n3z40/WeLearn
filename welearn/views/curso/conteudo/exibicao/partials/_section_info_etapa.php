<div id="div-infoetapa-saladeaula">
    <h3>Informações sobre a etapa atual</h3>
    <article id="art-modulo-infoetapa-saladeaula">
        <h4>Módulo <em><?php echo $modulo->nroOrdem ?></em>: "<em><?php echo $modulo->nome ?></em>"</h4>
        <dl>
            <dt><em>Descrição do Módulo:</em></dt>
            <dd><?php echo nl2br($modulo->descricao) ?></dd>
            <dt><em>Objetivos do Módulo:</em></dt>
            <dd><?php echo nl2br($modulo->objetivos) ?></dd>
        </dl>
    </article>
    <article id="art-avaliacao-infoetapa-saladeaula" <?php echo $avaliacao ? '' : 'style="display: none;"' ?>>
        <h4>Avaliação do Módulo <em><?php echo $modulo->nroOrdem ?></em>: "<em><?php echo $avaliacao ? $avaliacao->nome : '' ?></em>"</h4>
        <dl>
            <dt><em>Qtd. de Questões:</em></dt>
            <dd><?php echo $avaliacao ? $avaliacao->qtdQuestoesExibir : '0' ?></dd>
            <dt><em>Qtd. de Tentativas Permitidas:</em></dt>
            <dd><?php echo $avaliacao
                           ? $avaliacao->qtdTentativasPermitidas == 0
                                 ? 'Sem limites'
                                 : $avaliacao->qtdTentativasPermitidas
                           : '0' ?></dd>
            <dt><em>Tempo de Avaliação:</em></dt>
            <dd><?php echo $avaliacao ? $avaliacao->tempoDuracaoMax : '0' ?></dd>
        </dl>
    </article>
    <article id="art-aula-infoetapa-saladeaula" <?php echo $aula ? '' : 'style="display: none;"' ?>>
        <h4>Aula <em><?php echo $aula ? $aula->nroOrdem : '0' ?></em>: "<em><?php echo $aula ? $aula->nome : '' ?></em>"</h4>
        <dl>
            <dt><em>Descrição da Aula:</em></dt>
            <dd><?php echo $aula ? nl2br($aula->descricao) : '' ?></dd>
        </dl>
    </article>
    <article id="art-pagina-infoetapa-saladeaula" <?php echo $pagina ? '' : 'style="display: none;"' ?>>
        <h4>Página <em><?php echo $pagina ? $pagina->nroOrdem : '0' ?></em>: "<em><?php echo $pagina ? $pagina->nome : '' ?></em>"</h4>
    </article>
</div>
<hr>
<div id="div-navegacao-saladeaula">
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