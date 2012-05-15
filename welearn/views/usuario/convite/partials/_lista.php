<?php if($tipo=='enviados'):?>
<input type='hidden' id='tipo-convite' value='enviados'>
<?php foreach ($convites as $row):?>
    <li id='<?=$row->getId();?>'>
        <input type='hidden' class='id-convite' value='<?=$row->getId();?>'>
        <input type='hidden' class= 'id-remetente' value='<?=$row->getRemetente()->getId();?>'>
        <input type='hidden' class= 'id-destinatario' value='<?=$row->getDestinatario()->getId();?>'><?=$row->getDestinatario()->toHTML('imagem_pequena');?>
        <div class='mensagem'><?=$row->getMsgConvite();?></div>
        <div class='data'><?=date('d/m/Y à\s H:i',$row->getDataEnvio());?></div>
        <?=anchor('/convite/remover','cancelar',array('class'=>'remover-convite'))?>
    </li>
    <?php endforeach;?>
<?php elseif ($tipo == 'recebidos'): ?>
<input type='hidden' id='tipo-convite' value='recebidos'>
<?php foreach ($convites as $row):?>
    <li id='<?=$row->getId();?>'>
        <input type='hidden' class='id-convite' value='<?=$row->getId();?>'>
        <input type='hidden' class= 'id-remetente' value='<?=$row->getRemetente()->getId();?>'><?=$row->getRemetente()->toHTML('imagem_pequena');?>
        <input type='hidden' class= 'id-destinatario' value='<?=$row->getDestinatario()->getId();?>'>
        <div class='mensagem'><?=$row->getMsgConvite();?></div>
        <div class='data'><?=date('d/m/Y à\s H:i',$row->getDataEnvio());?></div>
        <?=anchor('/convite/aceitar','aceitar',array('class'=>'aceitar-convite'))?>
        <?=anchor('/convite/remover','recusar',array('class'=>'remover-convite'))?>
    </li>
    <?php endforeach;?>
<?php endif;?>




