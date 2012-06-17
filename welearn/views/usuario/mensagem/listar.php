<div id="mensagem-listar-content">
    <header>
        <hgroup>
            <h1 >Mensagens de <?=$amigo->nome?></h1>
            <input type="hidden" value='<?=$amigo->id?>' id='id-amigo-mensagens'/>
        </hgroup>
    </header>
</div>
<div>
    <?php if($haMensagens): ?>
            <nav id="paginacao-feeds">
            <a href="#" data-proximo="<?php echo $inicioProxPagina ?>" data-id-amigo="<?php echo $amigo->id ?>" id="paginacaoMensagem">mensagens mais antigas</a>
            <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
            </nav>
    <?php endif;?>
</div>
<div id="mensagem-lista-mensagens">
    <?php
    echo $listaMensagens;
    ?>
</div>
<?php if($amigo->configuracao->privacidadeMP == WeLearn_Usuarios_PrivacidadeMP::LIVRE ||
        ($amigo->configuracao->privacidadeMP == WeLearn_Usuarios_PrivacidadeMP::SO_AMIGOS
        && $saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS)
):?>
    <?php echo $enviarMensagem; ?>
<?php endif;?>



          

