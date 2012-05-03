<div id='listaUsuarios'>
<?php
foreach ($ResultadoBusca as $row):?>
    <div>
        <?php echo anchor('/perfil/'.$row->getId(), $row->getNome().' '.$row->getSobrenome()) ?>
    </br>
    <?= $row->getEmail()?>
    </div>
<?php endforeach; ?>
</div>

