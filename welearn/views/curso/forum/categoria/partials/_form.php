<fieldset>
    <legend>Dados da Categoria</legend>
    <dl>
        <dt><label for="txt-nome-categoria">Nome da categoria</label></dt>
        <dd><input type="text" id="txt-nome-categoria" value="<?php echo $nomeAtual ?>" name="nome" maxlength="80" /></dd>
        <dt><label for="txt-descricao-categoria">Descrição da categoria</label></dt>
        <dd><textarea name="descricao" id="txt-descricao-categoria" cols="50" rows="5"><?php echo $descricaoAtual ?></textarea></dd>
    </dl>
</fieldset>
