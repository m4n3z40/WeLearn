<div id="recurso-geral-content">
    <header>
        <hgroup>
            <h1>Lista Geral de Recursos do Curso</h1>
            <h3>Aqui são listados todos os recursos gerais contidos neste curso.</h3>
        </hgroup>
        <p>
            Deseja retornar ao index de recursos do curso?
            <?php echo anchor('/curso/conteudo/recurso/' . $idCurso, 'Clique aqui!') ?>
            <br><br>
            Ou <?php echo anchor('/curso/conteudo/recurso/criar/' . $idCurso,
                                 'Clique aqui para criar um novo recurso.') ?>
        </p>
    </header>
    <div>
    <?php if ($haRecursos): ?>
        <h4>Exibindo <em id="em-recurso-qtdexibindo"><?php echo $qtdExibindo ?></em>
            de <em id="em-recurso-qtdtotal"><?php echo $qtdTotal ?></em> Recursos Gerais.</h4>
        <table id="recurso-listar-datatable">
            <tr>
                <th>Nome / Descrição</th>
                <th>Enviado por / Enviado em</th>
                <th>Download</th>
                <th></th>
            </tr>
            <?php echo $listaRecursos ?>
        </table>
        <footer>
            <nav id="paginacao-recurso-geral">
                <?php if ($haMaisPaginas): ?>
                    <a href="#"
                       data-proximo="<?php echo $inicioProxPagina ?>"
                       data-id-curso="<?php echo $idCurso ?>"
                       class="button">Mais Recursos</a>
                <?php else: ?>
                    <h4>Não há mais recursos a serem exibidos no momento.</h4>
                <?php endif; ?>
            </nav>
        </footer>
    <?php else: ?>
        <h4>
            Nenhum recurso geral foi criado neste curso até o momento.
            <?php echo anchor('/curso/conteudo/recurso/criar/' . $idCurso,
                              'Crie o primeiro!') ?>
        </h4>
    <?php endif; ?>
    </div>
</div>