<h3>Escolaridade e Dados Profissionais</h3>
<?php echo form_open($formAction, $extraOpenForm) ?>
    <fieldset>
        <legend>Dados de Escolaridade</legend>
        <dl>
            <dt><label for="txt-escolaridade">Escolaridade</label></dt>
                <dd><input type="text" name="escolaridade" id="txt-escolaridade" value="<?php echo $escolaridadeAtual ?>" /></dd>
            <dt><label for="txt-escola">Escola</label></dt>
                <dd><input type="text" name="escola" id="txt-escola" value="<?php echo $escolaAtual ?>" /></dd>
            <dt><label for="txt-faculdade">Faculdade</label></dt>
                <dd><input type="text" name="faculdade" id="txt-faculdade" value="<?php echo $faculdadeAtual ?>" /></dd>
            <dt><label for="txt-curso">Curso</label></dt>
                <dd><input type="text" name="curso" id="txt-curso" value="<?php echo $cursoAtual ?>" /></dd>
            <dt><label for="txt-diploma">Diploma</label></dt>
                <dd><input type="text" name="diploma" id="txt-diploma" value="<?php echo $diplomaAtual ?>" /></dd>
            <dt><label for="txt-ano">Ano</label></dt>
                <dd><input type="text" name="ano" id="txt-ano" value="<?php echo $anoAtual ?>" /></dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>Dados Profissionais</legend>
        <dl>
            <dt><label for="txt-profissao">Profissão</label></dt>
                <dd><input type="text" name="profissao" id="txt-profissao" value="<?php echo $profissaoAtual ?>" </dd>
            <dt><label for="slt-area">Área de Trabalho</label></dt>
                <dd><?php echo form_dropdown('area', $listaAreas, $areaAtual, 'id="slt-area"') ?></dd>
            <dt><label for="slt-segmento">Segmento de Trabalho</label></dt>
                <dd><?php echo form_dropdown('segmento', $listaSegmentos, $segmentoAtual, 'id="slt-segmento"') ?></dd>
            <dt><label for="txt-empresa">Empresa/Organização</label></dt>
                <dd><input type="text" name="empresa" id="txt-empresa" value="<?php echo $empresaAtual ?>" /></dd>
            <dt><label for="txt-site-empresa">Site da Empresa</label></dt>
                <dd><input type="text" name="siteEmpresa" id="txt-site-empresa" value="<?php echo $siteEmpresaAtual ?>"></dd>
            <dt><label for="txt-cargo">Cargo</label></dt>
                <dd><input type="text" name="cargo" id="txt-cargo" value="<?php echo $cargoAtual ?>" /></dd>
            <dt><label for="txt-descricao-trabalho">Descrição das atividades do seu trabalho</label></dt>
                <dd><textarea cols="60" rows="10" name="descricaoTrabalho" id="txt-descricao-trabalho"><?php echo $descricaoTrabalhoAtual ?></textarea></dd>
            <dt><label for="txt-habilidades-profissionais">Habilidades Profissionais</label></dt>
                <dd><textarea cols="60" rows="10" name="habilidadesProfissionais" id="txt-habilidades-profissionais"><?php echo $habilidadesProfissionaisAtual ?></textarea></dd>
            <dt><label for="txt-interesses-profissionais">Interesses Profissionais</label></dt>
                <dd><textarea cols="60" rows="10" name="interessesProfissionais" id="txt-interesses-profissionais"><?php echo $interessesProfissionaisAtual ?></textarea></dd>
        </dl>
    </fieldset>
<?php echo form_close() ?>