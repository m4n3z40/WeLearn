<div id='feed-content'>
    <h1>Publique algo no TimeLine de <?=$usuarioPerfil->nome?></h1>
    <?=$criarFeed?>
    <hr>
    <h1>Publicações Recentes</h1>
    <ul id='feed-lista-feeds'>
        <?=$listarTimeline?>
    </ul>
    <?php if($haMaisPaginas):?>
    <footer>
        <nav id="paginacao-feeds">
            <a href=<?=$linkPaginacao?> data-proximo="<?php echo $inicioProxPagina ?>"  id="paginacao-feed">mais feeds</a>
            <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
        </nav>
    </footer>
    <?php else:?>
    <h4>
        Não Existem Novas Publicações Para Exibição!
    </h4>
    <?php endif;?>
</div>