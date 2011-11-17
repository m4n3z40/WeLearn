<fieldset>
    <legend>Dados do Fórum</legend>
    <dl>
        <dt><label for="txt-titulo-forum">Título</label></dt>
        <dd><input type="text" id="txt-titulo-forum" value="<?php echo $tituloAtual ?>" name="titulo" maxlength="80"></dd>
        <dt><label for="txt-descricao-forum">Descrição</label></dt>
        <dd><textarea name="descricao" id="txt-descricao-forum" cols="50" rows="5"><?php echo $descricaoAtual ?></textarea></dd>
    </dl>
</fieldset>
 
