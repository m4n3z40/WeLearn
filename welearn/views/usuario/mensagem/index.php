<div>
    <header>
        <hgroup>
            <h1>Mensagens de Amigos</h1>
            <h3>Selecione um dos amigos da lista para exibir as mensagens</h3>
        </hgroup>
    </header>
    <div>
        <?php if($success==true){ ?>
        <?php foreach ( $mensagens as $row):?>
            <?=anchor('/usuario/mensagem/listar/'.$row->id, $row->toHTML('imagem_pequena_sem_link'))?>
            </br>
        <?php endforeach;?>
        <?php }else{?>
        <h3>voce nao possui conversas com nenhum amigo</h3>
        <?php } ?>
    </div>
</div>