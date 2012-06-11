<div>
    <header>
        <hgroup>
            <h1>Mensagens Pessoais</h1>
            <h3>Selecione um dos Usuários da lista para exibir as Mensagens</h3>
        </hgroup>
    </header>
    <div>
        <?php if($success==true){ ?>
        <ul>
        <?php foreach ( $mensagens as $row):?>
            <li><?=anchor('/usuario/mensagem/listar/'.$row->id, $row->toHTML('imagem_pequena_sem_link'))?></li>
        <?php endforeach;?>
        </ul>
        <?php }else{?>
        <h3>Voce não possui conversas com nenhum Usuário</h3>
        <?php } ?>
    </div>
</div>