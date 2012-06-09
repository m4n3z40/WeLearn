<?php $comentarios= array_reverse($comentarios)?>
<?foreach($comentarios as $comentario):?>
    <li>
        <input type="hidden" value = '<?echo $comentario->id?>'>
        <?echo $comentario->criador->toHTML('imagem_mini');?>
        <div>comentou:</div>
        <p><?echo $comentario->conteudo;?></p>
        <div>Criado em:</div>
        <span><?php echo date('d/m/Y, à\s H:i', $comentario->dataEnvio) ?></span><br>
        <a href="comentario/remover"></a>
    </li>
<?endforeach;?>
