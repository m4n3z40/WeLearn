<?php $mensagens= array_reverse($mensagens)?>
<?php foreach($mensagens as $row):?>
<li class='item-lista-mensagem'>
    <article>
        <input type='hidden' class='id-mensagem' value='<?php echo $row->getId();?>'>
        <aside>
            <div class='remetente'>
                <?=$row->getRemetente()->toHTML('imagem_pequena')?>
            </div>
            <footer>
                <nav>
                    <ul>
                        <li>
                            <a href='usuario/mensagem/remover' class='remover-mensagem'>Remover mensagem</a>
                        </li>
                    </ul>
                </nav>
            </footer>
        </aside>
        <div>
            <div class='data-envio'><?=date('d/m/Y à\s H:i:s',$row->getDataEnvio())?></div>
            <div class='mensagem-texto'><?=nl2br($row->getMensagem())?></div>
        </div>
    </article>
</li>
<?php endforeach; ?>




