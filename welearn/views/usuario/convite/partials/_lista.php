<header>
<hgroup>
<h1>convites <?=$tipo?></h1>
</hgroup>
</header>
<ul>
    <div>
        <?php if($haConvites): ?>
        <a href="#" data-proximo="<?php echo $inicioProxPagina ?>">convites mais antigos</a>
        <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
        <?php endif;?>
    </div>
    <?php if($tipo=='enviados'):?>
    <?php foreach ($convites as $row):?>
        <li>
            <input type='hidden' class='id-convite' value='<?=$row->getId();?>'>
            <div class='destinatario'><?=$row->getDestinatario()->getId();?></div>
            <div class='mensagem'><?=$row->getMsgConvite();?></div>
            <div class='data'><?=date('d/m/Y à\s H:i',$row->getDataEnvio());?></div>
        </li>
    <?php endforeach;?>
    <?php elseif ($tipo == 'recebidos'): ?>
    <?php foreach ($convites as $row):?>
        <li>
            <input type='hidden' class='id-convite' value='<?=$row->getId();?>'>
            <div class='destinatario'><?=$row->getRemetente()->getId();?></div>
            <div class='mensagem'><?=$row->getMsgConvite();?></div>
            <div class='data'><?=date('d/m/Y à\s H:i',$row->getDataEnvio());?></div>
        </li>
        <?php endforeach;?>
    <?php endif;?>
</ul>

