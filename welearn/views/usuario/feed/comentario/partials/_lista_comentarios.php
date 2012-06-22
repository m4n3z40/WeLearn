<?php $comentarios= array_reverse($comentarios)?>
<?foreach($comentarios as $comentario):?>
<li>
    <aside>
        <?echo $comentario->criador->toHTML('imagem_mini');?>
        <input type="hidden" value = '<?echo $comentario->id?>'>
        <?echo anchor('comentario_feed/remover/'.$comentario->id,'Remover Comentário',array('id'=>'remover-comentario'))?>
    </aside>
    <div>
        <div>comentou:</div>
        <p><?echo $comentario->conteudo;?></p>
        <div>Criado em:</div>
        <span><?php echo date('d/m/Y, à\s H:i', $comentario->dataEnvio) ?></span><br>
    </div>
</li>
<?endforeach;?>
