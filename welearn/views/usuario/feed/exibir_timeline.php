<div id='feed-content'>
    <?php if($usuarioAutenticado->id != $usuarioPerfil->id):?>
        <?php if(($usuarioPerfil->configuracao->privacidadePerfil == WeLearn_Usuarios_PrivacidadePerfil::PUBLICO) || ($saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS)):?>
            <?php if($usuarioPerfil->configuracao->privacidadeCompartilhamento == WeLearn_Usuarios_PrivacidadeCompartilhamento::HABILITADO):?>
                <h1>Publique algo no TimeLine de <?=$usuarioPerfil->nome?></h1>
                    <?=$criarFeed?>
                <hr>
            <?php else:?>
                <h4>As configurações de privacidade de <?php echo $usuarioPerfil->nome?> não permitem o envio de compartilhamentos</h4>
            <?php endif;?>
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
        <?php else:?>
            <h4>As publicações deste usuário são restritas somente a Amigos</h4>
        <?php endif;?>
    <?php else:?>
        <h1>Publique algo para seus amigos</h1>
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
    <?php endif;?>
</div>