
<div id="amigos-listar-content">
    <header>
        <hgroup>
            <h1 >Lista de Amigos (<?=$totalAmigos ?>)</h1>
            <h3>Aqui é listado todos os seus amigos no WeLearn</h3>
        </hgroup>
    </header>
</div>
<?php if($success): ?>
    <ul id="ul-amigos-listar-lista" class="ul-grid-cursos-alunos">
         <?php echo $partialListaAmigos?>
    </ul>
    <div>
        <?php if($haAmigos):?>
            <a href="usuario/amigos/proxima_pagina/<?php echo $idUsuario?>/" id="paginacaoAmigo">Mais amigos</a>
            <input type='hidden' value='<?=$inicioProxPagina?>' id='id-prox-pagina'/>
        <?php endif;?>
    </div>
<?php else:?>
    <h4>Sua lista de Amigos está vazia, utilize a barra de busca e encontre seus Amigos!<h4>
<?php endif;?>

