<?php
/**
 * Created by JetBrains PhpStorm.
 * User: thiago
 * Date: 04/04/12
 * Time: 20:53
 * To change this template use File | Settings | File Templates.
 */
?>
            <div id="mensagem-lista-mensagens">
                <?php $ant=$mensagens[0]->getId();?>
                <?php $mensagens= array_reverse($mensagens)?>
                <?php foreach($mensagens as $row):?>
                    <header class='item-lista-mensagem'>
                        <input type='hidden' id='id-mensagem' value='<?php echo $row->getId();?>'>
                        <div class='imagem-remetente'>imagem remetente</div>
                        <div class='id-remetente'><?=$row->getRemetente()->getId()?></div>
                        <div class='mensagem-texto'><?=$row->getMensagem()?></div>
                        <div class='data-envio'><?=$row->getDataEnvio()?></div>
                        <a href='mensagem/remover' class='remover-mensagem'>remover</a>
                    </header>
                <?php endforeach; ?>
            </div>





