<fieldset id="fds-config-gerais">
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
