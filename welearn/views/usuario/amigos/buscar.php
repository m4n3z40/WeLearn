<div id="usuarios-buscar-content">
    <header>
        <hgroup>
            <h1>Busca de Usuarios do WeLearn</h1>
            <h3>Está procurando alguém? Está no lugar certo!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <header>
            <form method="get" action="<?php echo site_url($formAction) ?>" id="frm-buscar-cursos">
                <fieldset>
                    <legend>Quem você está procurando?</legend>
                    <dl>
                        <dt><input type="text" name="busca" id="txt-busca" placeholder="Descreva aqui." value="<?php echo $txtBusca ?>"></dt>
                    </dl>
                </fieldset>
                <input type="submit" id="btn-form-busca-usuarios" value="Buscar!">
            </form>
        </header>
        <div>

        </div>
    </div>
</div>
</header>
<div>
    <?php if ($haResultados): ?>
    <h3>Resultados para busca de <em>"<?php echo $txtBusca ?>"</em></h3>
    <ul id="ul-lista-resultados-busca-usuarios">
        <?php echo $resultadosBusca ?>
    </ul>
    <?php if ($haMaisPaginas): ?>
        <a href="usuario/amigos/mais_resultados"
           id="a-paginacao-busca-usuarios"
           data-proximo="<?php echo $inicioProxPagina ?>">Exibir mais resultados para <em>"<?php echo $txtBusca ?>"...</em></a>
        <?php else: ?>
        <h4>Não há mais resultados há serem exibidos.</h4>
        <?php endif; ?>
    <?php else: ?>
    <h4>Nenhuma busca foi realizada ou nenhum resultado foi encontrado :(</h4>
    <?php endif; ?>
</div>