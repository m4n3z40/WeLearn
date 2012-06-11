
<?php foreach ($listaAmigos as $row):?>
    <li id='item-amigo'>
        <?= $row->toHTML('imagem_pequena')?>
    </li>
<?php endforeach;?>


