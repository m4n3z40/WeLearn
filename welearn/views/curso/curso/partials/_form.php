<?php echo form_open_multipart($formAction, $extraOpenForm) ?>
    <fieldset>
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
            <dt><label for="slt-area">Área de Segmento</label></dt>
            <dd><?php echo form_dropdown('area', $listaAreas, $areaAtual, 'id="slt-area"') ?></dd>
            <dt><label for="slt-segmento">Segmento do Curso</label></dt>
            <dd><?php echo form_dropdown('segmento', $listaSegmentos, $segmentoAtual, 'id="slt-segmento"') ?></dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>Configurações Gerais</legend>
        <dl>
            <dt><label for="txt-tempo-duracao-max">Tempo máximo de duração do curso (Em horas)</label></dt>
            <dd><input type="number" name="tempoDuracaoMax" id="txt-tempo-duracao-max" step="any" value="<?php echo $tempoDuracaoMaxAtual ?>"/></dd>
            <dt><label for="ul-privacidade">Opções de Privacidade</label></dt>
            <dd>
                <ul id="ul-privacidade">
                    <li>
                        <input type="radio" id="rdo-privacidade-publico"
                            <?php echo ($privacidadeConteudoAtual === $conteudoPublico) ? 'checked="checked"' : '' ?>
                            name="privacidadeConteudo" value="<?php echo $conteudoPublico ?>"/>
                        <label for="rdo-privacidade-publico">Conteúdo do curso accessível para todos</label>
                    </li>
                    <li>
                        <input type="radio" id="rdo-privacidade-privado"
                            <?php echo ($privacidadeConteudoAtual === $conteudoPrivado) ? 'checked="checked"' : '' ?>
                            name="privacidadeConteudo" value="<?php echo $conteudoPrivado ?>"/>
                        <label for="rdo-privacidade-privado">Conteúdo do curso acessível somente para inscritos</label>
                    </li>
                    <li>
                        <input type="radio" id="rdo-inscricao-automatica"
                            <?php echo ($privacidadeInscricaoAtual === $inscricaoAutomatica) ? 'checked="checked"' : '' ?>
                            name="privacidadeInscricao" value="<?php echo $inscricaoAutomatica ?>"/>
                        <label for="rdo-inscricao-automatica">Inscrição dos alunos é aceita automáticamente</label>
                    </li>
                    <li>
                        <input type="radio" id="rdo-inscricao-restrita"
                            <?php echo ($privacidadeInscricaoAtual === $inscricaoRestrita) ? 'checked="checked"' : '' ?>
                            name="privacidadeInscricao" value="<?php echo $inscricaoRestrita ?>"/>
                        <label for="rdo-inscricao-restrita">Inscrição dos alunos fica em espera para aceitação dos Gerenciadores</label>
                    </li>
                </ul>
            </dd>
        </dl>
    </fieldset>
    <fieldset>
        <legend>Imagem de Exibição do Curso</legend>
        <dl>
            <?php if (!empty($imagemAtual)): ?>
            <dt><span>Imagem Atual</span></dt>
            <dd><?php echo $imagemAtual ?></dd>
            <?php endif ?>
            <dt><label for="fil-imagem">Escolha</label></dt>
            <dd>
                <div id="upload-img-holder" style="display: none;"></div>
                <input type="file" name="imagem" id="fil-imagem"/>
            </dd>
        </dl>
        <button type="submit" name="acao" value="<?php echo $acaoForm ?>" id="btn-form-curso"><?php echo $textoBotaoSubmit ?></button>
    </fieldset>
<?php echo form_close() ?>