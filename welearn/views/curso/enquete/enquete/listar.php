<div id="enquete-listar-content">
    <header>
        <hgroup>
            <h1>Lista de Enquetes do Curso <?php echo $tituloLista ?></h1>
            <h3>Abaixo são listada todas as enquetes contidas neste curso</h3>
        </hgroup>
        <?php if ( $alunoAutorizado ): ?>
        <?php echo gerar_menu_autorizado(array(
            array(
                'uri' => '/curso/enquete/criar/' . $idCurso,
                'texto' => 'Clique aqui!',
                'acao' => 'enquete/criar',
                'papel' => $papelUsuarioAtual
            ),
        ), array('<p>Quer adicionar uma nova enquete ao curso? ', '</p>')) ?>
        <?php endif; ?>
        <p>
            Fique à vontade para navegar pelos filtros.
        </p>
        <nav id="nav-filtros-lista-enquetes">
            <ul>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=todas', 'Todas as enquetes') ?> -
                    <span>(<?php echo $qtdTodas ?>)</span></li>

                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=ativas', 'Somente enquetes ativas') ?> -
                    <span>(<?php echo $qtdAtivas ?>)</span></li>
                <?php if (is_autorizado( $papelUsuarioAtual, 'enquete/alterar_status' )): ?>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=inativas', 'Somente enquetes inativas') ?> -
                    <span>(<?php echo $qtdInativas ?>)</span></li>
                <?php endif; ?>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=abertas', 'Somente enquetes abertas') ?> -
                    <span>(<?php echo $qtdAbertas ?>)</span></li>
                <li><?php echo anchor('/curso/enquete/listar/' . $idCurso . '?f=fechadas', 'Somente enquetes fechadas') ?> -
                    <span>(<?php echo $qtdFechadas ?>)</span></li>
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
            Nenhuma enquete foi criada neste fórum até o momento.
            <?php if ( $alunoAutorizado ): ?>
            <?php echo gerar_menu_autorizado(array(
                array(
                    'uri' => '/curso/enquete/criar/' . $idCurso,
                    'texto' => 'Seja o primeiro!',
                    'acao' => 'enquete/criar',
                    'papel' => $papelUsuarioAtual
                )
            )) ?>
            <?php endif; ?>
        </h4>
    <?php endif; ?>
    </div>
</div>