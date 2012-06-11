
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
                <h4>Não há mais resultados há serem exibidos!</h4>
        <?php endif;?>
        <?php else: ?>
                <h4>Nenhuma busca foi realizada ou nenhum resultado foi encontrado!</h4>
        <?php endif;?>
