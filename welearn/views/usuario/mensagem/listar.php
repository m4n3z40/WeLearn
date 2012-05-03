<div id="mensagem-listar-content">
    <header>
        <hgroup>
            <h1 >mensagens de <?=$nomeAmigo?></h1>
            <input type="hidden" value='<?=$idAmigo?>' id='id-amigo-mensagens'/>
        </hgroup>
    </header>
</div>
<div>
    <?php if($haMensagens){ ?>
    <a href="#" data-proximo="<?php echo $inicioProxPagina ?>" data-id-amigo="<?php echo $idAmigo ?>" id="paginacaoMensagem">mensagens mais antigas</a>
    <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
   <?php }?>
</div>
<?php
echo $listaMensagens;
?>
<?php echo $enviarMensagem;  ?>



          

