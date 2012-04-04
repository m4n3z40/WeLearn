<div id="mensagem-listar-content">
    <header>
        <hgroup>
            <h1>mensagens de <?=$idAmigo?></h1>
        </hgroup>
    </header>
    <?php if ($haMensagens): ?>
            <header id="paginacao-lista-mensagens">
                    <?php foreach($mensagens as $row):?>
                    <li><?=$row->getMensagem()?></br></li>
                    <li><?=$row->getRemetente()->getId()?></li>
                    <li><?=$row->getDataEnvio()?></li>
                    <?php $de=$row->getId(); ?>
                    <?php endforeach; ?>
                    <?php

                    echo anchor('/mensagem/listar/'.$idAmigo.'/'.$inicioProxPagina.'/10','proximo');
                    ?>
            </header>
    <?php else:?>
                <h1>Não existem novas mensagens</h1>
    <?php endif;?>
    </div>
          

