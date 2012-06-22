<div id="mensagem-listar-content">
    <header>
        <hgroup>
            <h1 >Mensagens de <?=$amigo->nome?></h1>
            <h3>Aqui é listada todas as mensagens trocadas entre você e <?php echo $amigo->nome ?></h3>
        </hgroup>
    </header>
    <div>
        <h3>Mensagens</h3>
        <div>
            <input type="hidden" value='<?=$amigo->id?>' id='id-amigo-mensagens'/>
            <?php if($haMensagens): ?>
            <nav id="paginacao-feeds">
            <a href="#" data-proximo="<?php echo $inicioProxPagina ?>" data-id-amigo="<?php echo $amigo->id ?>" id="paginacaoMensagem">mensagens mais antigas</a>
            <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
            </nav>
            <?php endif;?>
        </div>
        <ul id="mensagem-lista-mensagens">
        <?php
            echo $listaMensagens;
        ?>
        </ul>
        <hr>
        <?php if($amigo->configuracao->privacidadeMP == WeLearn_Usuarios_PrivacidadeMP::LIVRE ||
                ($amigo->configuracao->privacidadeMP == WeLearn_Usuarios_PrivacidadeMP::SO_AMIGOS
                && $saoAmigos == WeLearn_Usuarios_StatusAmizade::AMIGOS)
        ):?>
            <?php echo $enviarMensagem; ?>
        <?php endif;?>
    </div>
</div>



          

