<?php echo form_open('usuario/validar_cadastro') ?>
    <fieldset>
        <legend>Novo por aqui? Cadastre-se!</legend>
        <dl>
            <dt><label for="txt-nome">Nome</label></dt>
                <dd><input type="text" name="nome" id="txt-nome" maxlength="45" placeholder="Seu nome" /></dd>
            <dt><label for="txt-sobrenome">Sobrenome</label></dt>
                <dd><input type="text" name="sobrenome" id="txt-sobrenome" maxlength="60" placeholder="Seu sobrenome" /></dd>
            <dt><label for="txt-email">Email</label></dt>
                <dd><input type="text" name="email" id="txt-email" maxlength="80" placeholder="Seu email" /></dd>
            <dt><label for="txt-nome-usuario">Nome de usuário</label></dt>
                <dd><input type="text" name="nomeUsuario" id="txt-nome-usuario" maxlength="40" placeholder="Seu nome de usuário" ></dd>
            <dt><label for="txt-senha">Senha</label></dt>
                <dd><input type="password" name="senha" id="txt-senha" maxlength="24" placeholder="Sua senha" /></dd>
            <dt><label for="txt-senha-confirm">Coonfirme a senha</label></dt>
                <dd><input type="password" name="senhaConfirm" id="txt-senha-confirm" maxlength="24" placeholder="Confirme a senha" /></dd>
            <dt><label for="txt-area">Área de interesse</label></dt>
                <dd><input type="text" name="area" id="txt-area" placeholder="Em qual área você se interessa?" /></dd>
            <dt><label for="txt-segmento">Segmento de interesse</label></dt>
                <dd><input type="text" name="segmento" id="txt-segmento" placeholder="Qual segmento desta área te atrai mais?" /></dd>
        </dl>
        <button type="submit" name="cadastrar" value="1">Cadastrar!</button>
    </fieldset>
<?php echo form_close() ?>