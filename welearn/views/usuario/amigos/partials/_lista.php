

<?php foreach ($listaAmigos as $row):?>
    <li id='item-amigo'>
        <a href="#"><?=$row->getNome()?> <?=$row->getSobrenome()?></a>
    </li>
<?php endforeach;?>


