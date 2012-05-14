
<div id="amigos-listar-content">
    <header>
        <hgroup>
            <h1 >Lista de Amigos(<?=$totalAmigos ?>)</h1>
        </hgroup>
    </header>
</div>

<?php if($success): ?>
    <ul id='ul-amigos-listar-lista'>
         <?php echo $partialListaAmigos?>
    </ul>
    <div>
        <?php if($haAmigos):?>
            <a href="usuario/amigos/proxima_pagina" id="paginacaoAmigo">Mais amigos</a>
            <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
        <?php endif;?>
    </div>
<?php else:?>
    Sua Lista de Amigos Está Vazia, Utilize A Barra de Busca e Encontre Seus Amigos!
<?php endif;?>

