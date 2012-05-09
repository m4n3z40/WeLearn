<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 05/05/12
 * Time: 23:10
 * To change this template use File | Settings | File Templates.
 */
?>
<div id="container-convite-pendente" title="Convite pendente" style="display: none;">
    <?php echo form_open(base_url(),array('id' => 'form-convite-pendente'))?>
    <?php echo form_hidden('id-convite',$convite_pendente->getId());?>
    <?php if($convite_pendente ->getDestinatario() == $usuarioAutenticado ):?>
    <?php $remetente=$convite_pendente->getRemetente();?>
    <div>Voce possui uma solicitaçao de amizade pendente enviada por <?=$remetente->getNome().' '.$remetente->getSobreNome()?></div>
    <div><?= $convite_pendente->getMsgConvite();?></div>
    <?php else:?>
    <?php $destinatario=$convite_pendente->getDestinatario();?>
    <div>Voce já enviou uma solicitacao de amizade para <?=$destinatario->getNome().' '.$destinatario->getSobreNome()?></div>
    <div><?= $convite_pendente->getMsgConvite();?></div>
    <?php endif;?>
    <?php echo form_close()?>
</div>