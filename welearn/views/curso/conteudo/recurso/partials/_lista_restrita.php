<?php if ($haRecursos): ?>
    <h4>Exibindo <em id="em-recurso-qtdexibindo"><?php echo $qtdExibindo ?></em>
        de <em id="em-recurso-qtdtotal"><?php echo $qtdTotal ?></em> Recursos Restritos.</h4>
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
        <nav id="paginacao-recurso-restrito">
            <?php if ($haMaisPaginas): ?>
                <a href="#"
                   data-proximo="<?php echo $inicioProxPagina ?>"
                   data-id-aula="<?php echo $idAula ?>"
                   class="button">Mais Recursos</a>
            <?php else: ?>
                <h4>Não há mais recursos a serem exibidos no momento.</h4>
            <?php endif; ?>
        </nav>
    </footer>
<?php else: ?>
    <h4>
        Nenhum recurso restrito foi criado nesta aula até o momento.
        <?php echo anchor('/curso/conteudo/recurso/criar/' . $idCurso,
                          'Crie o primeiro!') ?>
    </h4>
<?php endif; ?>