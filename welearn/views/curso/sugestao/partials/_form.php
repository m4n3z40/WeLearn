<?php echo form_open($formAction, $extraOpenForm) ?>
    <fieldset>
        <legend><?php echo $tituloForm ?></legend>
        <dl>
            <dt><label for="txt-nome">Nome do Curso</label></dt>
                <dd><input type="text" name="nome" maxlength="80" id="txt-nome" value="<?php echo $nomeAtual ?>" /></dd>
            <dt><label for="txt-tema">Tema do Curso</label></dt>
                <dd><textarea rows="5" cols="50" name="tema" id="txt-tema"><?php echo $temaAtual ?></textarea></dd>
            <dt><label for="txt-descricao">Descrição</label></dt>
                <dd><textarea rows="5" cols="50" name="descricao" id="txt-descricao"><?php echo $descricaoAtual ?></textarea></dd>
            <dt><label for="slt-area">Área de Segmento</label></dt>
                <dd><?php form_dropdown('area', $listaAreas, $areaAtual, 'id="slt-area"') ?></dd>
            <dt><label for="slt-segmento">Segmento do Curso</label></dt>
                <dd><?php form_dropdown('segmento', $listaSegmentos, $segmentoAtual, 'id="slt-segmento"') ?></dd>
        </dl>
        <button type="submit" name="sugerir" id="btn-sugerir">Enviar Sugestão</button>
    </fieldset>
<?php echo form_close() ?>
