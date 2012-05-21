<h3>Configurações de Privacidade</h3>
<?php echo form_open($formAction, $extraOpenForm) ?>
<fieldset>
    <legend>Configurações Avançadas</legend>
    <dl>
        <dt><span>Privacidade do Perfil:</span></dt>
        <dd>
            <ul>
                <li><input type="radio"
                           name="privacidadePerfil"
                           value="<?php echo WeLearn_Usuarios_PrivacidadePerfil::PUBLICO ?>"
                           id="rdo-privacidade-perfil-publico"
                           <?php echo ( $privacidadePerfilAtual === WeLearn_Usuarios_PrivacidadePerfil::PUBLICO )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-perfil-publico"><?php echo WeLearn_Usuarios_PrivacidadePerfil::getDescricao(WeLearn_Usuarios_PrivacidadePerfil::PUBLICO) ?></label></li>
                <li><input type="radio"
                           name="privacidadePerfil"
                           value="<?php echo WeLearn_Usuarios_PrivacidadePerfil::PRIVADO ?>"
                           id="rdo-privacidade-perfil-privado"
                           <?php echo ( $privacidadePerfilAtual === WeLearn_Usuarios_PrivacidadePerfil::PRIVADO )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-perfil-privado"><?php echo WeLearn_Usuarios_PrivacidadePerfil::getDescricao(WeLearn_Usuarios_PrivacidadePerfil::PRIVADO) ?></label></li>
            </ul>
        </dd>
        <dt><span>Privacidade de Mensagens Pessoais</span></dt>
        <dd>
            <ul>
                <li><input type="radio"
                           name="privacidadeMP"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeMP::LIVRE ?>"
                           id="rdo-privacidade-mp-livre"
                           <?php echo ( $privacidadeMPAtual === WeLearn_Usuarios_PrivacidadeMP::LIVRE )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-mp-livre"><?php echo WeLearn_Usuarios_PrivacidadeMP::getDescricao(WeLearn_Usuarios_PrivacidadeMP::LIVRE) ?></label></li>
                <li><input type="radio"
                           name="privacidadeMP"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeMP::SO_AMIGOS ?>"
                           id="rdo-privacidade-mp-amigos"
                           <?php echo ( $privacidadeMPAtual === WeLearn_Usuarios_PrivacidadeMP::SO_AMIGOS )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-mp-amigos"><?php echo WeLearn_Usuarios_PrivacidadeMP::getDescricao(WeLearn_Usuarios_PrivacidadeMP::SO_AMIGOS) ?></label></li>
                <li><input type="radio"
                           name="privacidadeMP"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeMP::DESABILITADO ?>"
                           id="rdo-privacidade-mp-desabilitado"
                           <?php echo ( $privacidadeMPAtual === WeLearn_Usuarios_PrivacidadeMP::DESABILITADO )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-mp-desabilitado"><?php echo WeLearn_Usuarios_PrivacidadeMP::getDescricao(WeLearn_Usuarios_PrivacidadeMP::DESABILITADO) ?></label></li>
            </ul>
        </dd>
        <dt><span>Privacidade de Convites de Cursos</span></dt>
        <dd>
            <ul>
                <li><input type="radio"
                           name="privacidadeConvites"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeConvites::LIVRE ?>"
                           id="rdo-privacidade-convites-livre"
                           <?php echo ( $privacidadeConvitesAtual === WeLearn_Usuarios_PrivacidadeConvites::LIVRE )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-convites-livre"><?php echo WeLearn_Usuarios_PrivacidadeConvites::getDescricao(WeLearn_Usuarios_PrivacidadeConvites::LIVRE) ?></label></li>
                <li><input type="radio"
                           name="privacidadeConvites"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeConvites::SO_AMIGOS ?>"
                           id="rdo-privacidade-convites-amigos"
                           <?php echo ( $privacidadeConvitesAtual === WeLearn_Usuarios_PrivacidadeConvites::SO_AMIGOS )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-convites-amigos"><?php echo WeLearn_Usuarios_PrivacidadeConvites::getDescricao(WeLearn_Usuarios_PrivacidadeConvites::SO_AMIGOS) ?></label></li>
                <li><input type="radio"
                           name="privacidadeConvites"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeConvites::DESABILITADO ?>"
                           id="rdo-privacidade-convites-desabilitado"
                           <?php echo ( $privacidadeConvitesAtual === WeLearn_Usuarios_PrivacidadeConvites::DESABILITADO )
                                      ? 'checked="checked"' : '' ?>
                    ><label for="rdo-privacidade-convites-desabilitado"><?php echo WeLearn_Usuarios_PrivacidadeConvites::getDescricao(WeLearn_Usuarios_PrivacidadeConvites::DESABILITADO) ?></label></li>
            </ul>
        </dd>
        <dt><span>Feed de Compartilhamento</span></dt>
        <dd>
            <ul>
                <li><input type="radio"
                           name="privacidadeCompartilhamento"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeCompartilhamento::HABILITADO ?>"
                           id="rdo-privacidade-compartilhamento-habilitado"
                           <?php echo ( $privacidadeCompartilhamentoAtual === WeLearn_Usuarios_PrivacidadeCompartilhamento::HABILITADO )
                                      ? 'checked="checked"' : '' ?>
                    ><label
                    for="rdo-privacidade-compartilhamento-habilitado"><?php echo WeLearn_Usuarios_PrivacidadeCompartilhamento::getDescricao(WeLearn_Usuarios_PrivacidadeCompartilhamento::HABILITADO) ?></label></li>
                <li><input type="radio"
                           name="privacidadeCompartilhamento"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeCompartilhamento::DESABILITADO ?>"
                           id="rdo-privacidade-compartilhamento-desabilitado"
                           <?php echo ( $privacidadeCompartilhamentoAtual === WeLearn_Usuarios_PrivacidadeCompartilhamento::DESABILITADO )
                                      ? 'checked="checked"' : '' ?>
                    ><label
                    for="rdo-privacidade-compartilhamento-desabilitado"><?php echo WeLearn_Usuarios_PrivacidadeCompartilhamento::getDescricao(WeLearn_Usuarios_PrivacidadeCompartilhamento::DESABILITADO) ?></label></li>
            </ul>
        </dd>
        <dt><span>Notificações de Eventos</span></dt>
        <dd>
            <ul>
                <li><input type="radio"
                           name="privacidadeNotificacoes"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeNotificacoes::HABILITADO ?>"
                           id="rdo-privacidade-notificacoes-habilitado"
                           <?php echo ( $privacidadeNotificacoesAtual === WeLearn_Usuarios_PrivacidadeNotificacoes::HABILITADO )
                                      ? 'checked="checked"' : '' ?>
                    ><label
                    for="rdo-privacidade-notificacoes-habilitado"><?php echo WeLearn_Usuarios_PrivacidadeNotificacoes::getDescricao(WeLearn_Usuarios_PrivacidadeNotificacoes::HABILITADO) ?></label></li>
                <li><input type="radio"
                           name="privacidadeNotificacoes"
                           value="<?php echo WeLearn_Usuarios_PrivacidadeNotificacoes::DESABILITADO ?>"
                           id="rdo-privacidade-notificacoes-desabilitado"
                           <?php echo ( $privacidadeNotificacoesAtual === WeLearn_Usuarios_PrivacidadeNotificacoes::DESABILITADO )
                                      ? 'checked="checked"' : '' ?>
                    ><label
                    for="rdo-privacidade-notificacoes-desabilitado"><?php echo WeLearn_Usuarios_PrivacidadeNotificacoes::getDescricao(WeLearn_Usuarios_PrivacidadeNotificacoes::DESABILITADO) ?></label></li>
            </ul>
        </dd>
    </dl>
</fieldset>
<?php echo form_close() ?>