<div id="mensagem-lista-mensagens">
    <?php $mensagens= array_reverse($mensagens)?>
    <?php foreach($mensagens as $row):?>
        <li class='item-lista-mensagem'>
            <input type='hidden' id='id-mensagem' value='<?php echo $row->getId();?>'>
            <div class='imagem-remetente'>imagem remetente</div>
            <div class='id-remetente'><?=$row->getRemetente()->getId()?></div>
            <div class='mensagem-texto'><?=$row->getMensagem()?></div>
            <div class='data-envio'><?=$row->getDataEnvio()?></div>
            <a href='usuario/mensagem/remover' class='remover-mensagem'>remover</a>
        </li>
    <?php endforeach; ?>
</div>





