<h3>Ações em Amigos</h3>
<ul>
    <li><?=anchor('usuario/amigos/buscar','Buscar Amigos');?></li>
    <li><?=anchor('/convite/index/enviados','Convites Enviados');?></li>
    <li><?=anchor('/convite/index/recebidos','Convites Recebidos');?></li>
    <li><?=anchor(site_url('/usuario/amigos/listar/'.$idUsuario),'Amigos')?></li>
</ul>