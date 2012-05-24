<div id="configuracao-usuario-index-content">
    <header>
        <hgroup>
            <h1>Configurações de Usuário</h1>
            <h3>Dê uma olhada no menu à direita e veja o que você pode fazer por aqui!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <h3>Aqui você pode alterar suas informações! Isso torna você único!</h3>
        <h4>O que quer configurar?</h4>
        <nav>
            <ul>
                <li><?php echo anchor('/usuario/configuracao/dados_pessoais', 'Dados Pessoais') ?></li>
                <li><?php echo anchor('/usuario/configuracao/dados_profissionais', 'Dados Profissionais') ?></li>
                <li><?php echo anchor('/usuario/configuracao/imagem', 'Imagem de Exibição') ?></li>
                <li><?php echo anchor('/usuario/configuracao/privacidade', 'Privacidade') ?></li>
            </ul>
        </nav>
    </div>
</div>