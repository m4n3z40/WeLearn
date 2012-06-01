
<div id='feed-content'>
    <h1>Compartilhe Algo Com Seus Amigos</h1>
    <?=$criarFeed?>
    <hr>
    <h1>Publicações Recentes</h1>
        <ul id='feed-lista-feeds'>
            <?=$listarFeed?>
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

