<div id="categoria-forum-listar-content">
    <header>
        <hgroup>
            <h1>Lista de Categorias de fórum do Curso</h1>
            <h3>Abaixo você verá todas as categorias de fórum dinponíveis</h3>
        </hgroup>
        <p>
            Quer adicionar uma categoria de fórum? <?php echo anchor('/curso/forum/categoria/criar/' . $idCurso, 'É por aqui!') ?>
        </p>
    </header>
    <div>
    <?php if ($haCategorias): ?>
        <table id="categoria-forum-listar-datatable">
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Data de Criação</th>
                <th>Criador</th>
                <th></th>
                <th></th>
            </tr>
            <?php echo $listaCategorias ?>
        </table>
        <footer>
            <nav id="paginacao-categoria-forum">
                <?php if ($haMaisPaginas): ?>
                    <a href="#" data-proximo="<?php echo $inicioProxPagina ?>" data-id-curso="<?php echo $idCurso ?>" class="button">Categorias mais antigas</a>
                <?php else: ?>
                    <h4>Não há mais categorias a serem exibidas no momento.</h4>
                <?php endif; ?>
            </nav>
        </footer>
    <?php else: ?>
        <h4>
            Nenhuma categoria de fórum foi criada para este curso até o momento. <?php echo anchor('/curso/forum/categoria/criar/' . $idCurso, 'Seja o primeiro') ?>
        </h4>
    <?php endif ?>
    </div>
</div>
 
