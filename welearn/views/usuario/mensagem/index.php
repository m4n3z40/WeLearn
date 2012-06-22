<div id="usuario-mensagem-index-content">
    <header>
        <hgroup>
            <h1>Mensagens Pessoais</h1>
            <h3>Selecione um dos Usuários da lista para exibir as Mensagens</h3>
            <p>Aqui é exibido todos os amigos com quem você ja trocou mensagem no WeLearn até agora.</p>
        </hgroup>
    </header>
    <div>
        <h3>Conversas com Usuários</h3>
        <?php if($success==true){ ?>
        <ul id="ul-lista-usuarios-mensagens">
        <?php foreach ( $mensagens as $row):?>
            <li><?=anchor('/usuario/mensagem/listar/'.$row->id, $row->toHTML('imagem_pequena_sem_link'))?></li>
        <?php endforeach;?>
        </ul>
        <?php }else{?>
        <h3>Voce não possui conversas com nenhum Usuário</h3>
        <?php } ?>
    </div>
</div>