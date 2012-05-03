<ul>
<? foreach ($convites as $row):?>
    <li>
        <input type='hidden' class='id-convite' value='<?=$row->getId()?>'>
        <input type='hidden' class='id-remetente' value='<?=$row->getRemetente()->getId()?>'>
        <div><?=$row->getRemetente()->getNome()?></div>
        <div><?=$row->getMsgConvite()?></div>
        <a href="#" class='aceitar-convite'>aceitar</a>
        <a href="convite/remover" class='remover-convite'>recusar</a>
    </li>
<? endforeach;?>
</ul>