<div id="notificacao-listar-content">
    <header>
        <hgroup>
            <h1>Suas Notificações</h1>
            <h3>Aqui você ficará atualizado sobre o que acontece no WeLearn em relação a você</h3>
        </hgroup>
        <p>Nós notificamos você sobre o rola por aqui, você não precisa ficar procurando nada ;)</p>
    </header>
    <div>
        <?php if ($haNotificacoes): ?>
        <header>
            <h4>Há <em><?php echo $totalNotificacoesNovas ?></em> notificação(ões) nova(s)</h4>
        </header>
        <ul id="ul-lista-notificacoes">
            <?php echo $listaNotificacoes ?>
        </ul>
            <?php if ($haProxPagina): ?>
            <footer>
                <a href="#"
                   data-proximo="<?php echo $inicioProxPagina ?>"
                   id="a-paginacao-notificacao">Exibir mais notificações mais antigas...</a>
            </footer>
            <?php endif; ?>
        <?php else: ?>
        <h4>Não há notificações a serem exibidas no momento.</h4>
        <?php endif; ?>
    </div>
</div>