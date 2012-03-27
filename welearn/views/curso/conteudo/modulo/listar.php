<div id="modulo-listar-content">
    <header>
        <hgroup>
            <h1>Lista de Módulos do Curso</h1>
            <h3>Abaixo você verá todos módulos contidos neste curso.</h3>
        </hgroup>
        <p>
            Quer criar um novo módulo de curso? <?php echo anchor('/curso/conteudo/modulo/criar/' . $idCurso, 'É por aqui!') ?>
        </p>
    </header>
    <div>
    <?php if ($haModulos): ?>
        <ul id="ul-modulo-listar-lista">
            <?php echo $listaModulos ?>
        </ul>
        <footer>
            <nav id="paginacao-modulo">
                <?php if ($haMaisPaginas): ?>
                    <a href="#" data-proximo="<?php echo $inicioProxPagina ?>" data-id-curso="<?php echo $idCurso ?>" class="button">Mais Módulos...</a>
                <?php else: ?>
                    <h4>Não há mais módulos a serem exibidos no momento.</h4>
                <?php endif; ?>
            </nav>
        </footer>
    <?php else: ?>
        <h4>
            Nenhum módulo de curso foi criado até o momento. <?php echo anchor('/curso/conteudo/modulo/criar/' . $idCurso, 'Seja o primeiro!') ?>
        </h4>
    <?php endif ?>
    </div>
</div>