
<div id='feed-content'></div>
    <h1>Compartilhe Algo Com Seus Amigos</h1>
    <?=$criarFeed?>
    <hr>
    <h1>Feeds Recentes</h1>
        <ul id='feed-lista-feeds'>
            <?=$listarFeed?>
        </ul>
        <?php if($haMaisPaginas):?>
            <footer>
            <nav id="paginacao-feeds">
                <a href="/home/proxima_pagina" data-proximo="<?php echo $inicioProxPagina ?>"  id="paginacao-feed">mais feeds</a>
                <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
            </nav>
            </footer>
        <?php else:?>
            <h4>
                Voce não possui nenhum feed para exibição!
            </h4>
        <?php endif;?>
</div>

