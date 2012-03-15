<div id="enquete-listar-content">
    <header>
        <h1>Lista de Enquetes do Curso</h1>
        <h3>Abaixo são listada todas as enquetes contidas neste curso</h3>
        <p>
            Quer adicionar uma nova enquete ao curso? <?php echo anchor('/curso/enquete/criar/' . $idCurso, 'Clique aqui!') ?>
            <br>
            Fique à vontade para navegar pelos filtros.
        </p>
        <nav id="nav-filtros-lista-enquetes">
            <ul>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=todas', 'Todas as enquetes') ?></li>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=ativas', 'Somente enquetes ativas') ?></li>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=inativas', 'Somente enquetes inativas') ?></li>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=abertas', 'Somente enquetes abertas') ?></li>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=fechadas', 'Somente enquetes fechadas') ?></li>
            </ul>
        </nav>
    </header>
    <div>
    <?php if ($haEnquetes): ?>
        <table id="enquete-listar-datatable">
            <?php echo $listaEnquetes ?>
        </table>
        <footer>
            <nav id="paginacao-enquete">
                <?php if ($haMaisPaginas): ?>
                    <a href="#" data-proximo="<?php echo $inicioProxPagina ?>" data-id-curso="<?php echo $idCurso ?>" class="button">Enquetes mais antigas</a>
                <?php else: ?>
                    <h4>Não há mais enquetes para serem exibidas no momento.</h4>
                <?php endif; ?>
            </nav>
        </footer>
    <?php else: ?>
        <h4>
            Nenhuma enquete foi criada neste fórum até o momento. <?php echo anchor('/curso/enquete/criar/' . $idCurso, 'Seja o primeiro!') ?>
        </h4>
    <?php endif; ?>
    </div>
</div>