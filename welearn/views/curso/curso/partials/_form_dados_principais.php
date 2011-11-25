<fieldset id="fds-dados-principais">
    <legend>Dados Principais</legend>
    <dl>
        <dt><label for="txt-nome">Nome do Curso</label></dt>
        <dd><input type="text" name="nome" maxlength="80" id="txt-nome" value="<?php echo $nomeAtual ?>" /></dd>
        <dt><label for="txt-tema">Tema do Curso</label></dt>
        <dd><textarea rows="5" cols="50" name="tema" id="txt-tema"><?php echo $temaAtual ?></textarea></dd>
        <dt><label for="txt-descricao">Descrição</label></dt>
        <dd><textarea rows="5" cols="50" name="descricao" id="txt-descricao"><?php echo $descricaoAtual ?></textarea></dd>
        <dt><label for="txt-objetivos">Objetivos</label></dt>
        <dd><textarea rows="10" cols="50" name="objetivos" id="txt-objetivos"><?php echo $objetivosAtual ?></textarea></dd>
        <dt><label for="txt-conteudo-proposto">Conteúdo Proposto</label></dt>
        <dd><textarea name="conteudoProposto" id="txt-conteudo-proposto" cols="50" rows="10"><?php echo $conteudoPropostoAtual ?></textarea></dd>
        <?php if ($acaoForm == 'criarFromSugestao'): ?>
            <dt><span>Área de Segmento</span></dt>
            <dd><?php echo $sugestao->segmento->area->descricao ?></dd>
            <dt><span>Segmento do Curso</span></dt>
            <dd><?php echo $sugestao->segmento->descricao ?></dd>
        <?php else: ?>
            <dt><label for="slt-area">Área de Segmento</label></dt>
            <dd><?php echo form_dropdown('area', $listaAreas, $areaAtual, 'id="slt-area"') ?></dd>
            <dt><label for="slt-segmento">Segmento do Curso</label></dt>
            <dd><?php echo form_dropdown('segmento', $listaSegmentos, $segmentoAtual, 'id="slt-segmento"') ?></dd>
        <?php endif ?>
    </dl>
</fieldset>
 
