<fieldset id="fds-config-avancadas">
    <legend>Configurações Avançadas</legend>
    <dl>
        <dt><span>Status do Conteúdo do Curso</span></dt>
        <dd>
            <ul>
                <li>
                    <input type="radio" id="rdo-conteudo-bloqueado"
                           <?php echo ($statusAtual === $conteudoBloqueado) ? 'checked="checked"' : '' ?>
                           name="status" value="<?php echo $conteudoBloqueado ?>" />
                    <label for="rdo-conteudo-bloqueado">Conteúdo Bloqueado</label>
                </li>
                <li>
                    <input type="radio" id="rdo-conteudo-aberto"
                           <?php echo ($statusAtual === $conteudoAberto) ? 'checked="checked"' : '' ?>
                           name="status" value="<?php echo $conteudoAberto ?>" />
                    <label for="rdo-conteudo-aberto">Conteúdo Aberto</label>
                </li>
            </ul>
        </dd>
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
                <li>
                    <input type="radio" id="rdo-criacao-forum-aberto"
                           <?php echo ($permissaoCriacaoForumAtual === $criacaoForumAberta) ? 'checked="checked"' : '' ?>
                           name="permissaoCriacaoForum" value="<?php echo $criacaoForumAberta ?>" />
                    <label for="rdo-criacao-forum-aberto">Alunos podem criar tópicos em fóruns</label>
                 </li>
                <li>
                    <input type="radio" id="rdo-criacao-forum-restrito"
                           <?php echo ($permissaoCriacaoForumAtual === $criacaoForumRestrita) ? 'checked="checked"' : '' ?>
                           name="permissaoCriacaoForum" value="<?php echo $criacaoForumRestrita ?>" />
                    <label for="rdo-criacao-forum-restrito">Somente Gerenciadores, Moderadores ou Instrutores podem criar tópicos em fóruns</label>
                </li>
                <li>
                    <input type="radio" id="rdo-criacao-enquete-aberto"
                           <?php echo ($permissaoCriacaoEnqueteAtual === $criacaoEnqueteAberta) ? 'checked="checked"' : '' ?>
                           name="permissaoCriacaoEnquete" value="<?php echo $criacaoEnqueteAberta ?>" />
                    <label for="rdo-criacao-enquete-aberto">Alunos podem criar enquetes</label>
                </li>
                <li>
                    <input type="radio" id="rdo-criacao-enquete-restrito"
                           <?php echo ($permissaoCriacaoEnqueteAtual === $criacaoEnqueteRestrita) ? 'checked="checked"' : '' ?>
                           name="permissaoCriacaoEnquete" value="<?php echo $criacaoEnqueteRestrita ?>" />
                    <label for="rdo-criacao-enquete-restrito">Somente Gerenciadores, Moderadores ou Instrutores podem criar enquetes</label>
                </li>
            </ul>
        </dd>
    </dl>
</fieldset>