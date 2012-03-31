<?php foreach($mensagens as $row):?>
   <li><?=$row->getMensagem()?></br></li>
   <li><?=$row->getRemetente()->getId()?></li>
   <li><?=$row->getDataEnvio()?></li>
<?endforeach;?>
