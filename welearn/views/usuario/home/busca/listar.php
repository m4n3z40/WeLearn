
<div id="usuarios-listar-content">
    <header>
        <hgroup>
            <h1 >Resultados da busca</h1>
        </hgroup>
    </header>
</div>


        <?php
            echo $listaUsuarios;
            if($success == true):
            if($paginacao['proxima_pagina']):
        ?>
            <div>
                <a href="#" id='proximaPaginaUsuarios'>mais resultados</a>
                <input type='hidden' value='<?=$paginacao['inicio_proxima_pagina']?>' id='id-prox-pagina'/>
                <input type='hidden' value='<?=$texto?>' id='texto'/>
            </div>
        <?php else: ?>
                <h1>não existem mais resultados</h1>
        <?php endif;?>
        <?php else: ?>
                <h1>nenhum resultado encontrado</h1>
        <?php endif;?>
