<div id='listaUsuarios'>
<?php
foreach ($ResultadoBusca as $row):?>
    <div>
    <a href='#'><?= $row->getNome().' '.$row->getSobrenome()?></a>
    </br>
    <?= $row->getEmail()?>
    </div>
<?php endforeach; ?>
</div>

