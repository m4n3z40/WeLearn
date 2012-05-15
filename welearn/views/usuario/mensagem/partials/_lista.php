
<?php $mensagens= array_reverse($mensagens)?>
<?php foreach($mensagens as $row):?>
    <li class='item-lista-mensagem'>
        <input type='hidden' id='id-mensagem' value='<?php echo $row->getId();?>'>
        <div class='remetente'><?=$row->getRemetente()->toHTML('imagem_pequena')?></div>
        <div class='mensagem-texto'><?=$row->getMensagem()?></div>
        <div class='data-envio'><?=date('d/m/Y à\s H:i',$row->getDataEnvio())?></div>
        <a href='usuario/mensagem/remover' class='remover-mensagem'>remover</a>
    </li>
    </br>
<?php endforeach; ?>




