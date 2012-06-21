<div id="recurso-restrito-content">
    <header>
        <hgroup>
            <h1>Lista Restrita de Recursos do Curso</h1>
            <h3>Aqui são listados todos os recursos restritos contidos na aula escolhida.</h3>
        </hgroup>
        <p>
            Deseja retornar ao index de recursos do curso?
            <?php echo anchor('/curso/conteudo/recurso/' . $idCurso, 'Clique aqui!') ?>
            <br><br>
            Ou <?php echo anchor('/curso/conteudo/recurso/criar/' . $idCurso,
                                 'Clique aqui para criar um novo recurso.') ?>
        </p>
        <nav>
            <ul>
                <li>
                    <h4>Escolha o módulo:</h4>
                    <?php echo $selectModulos ?>
                </li>
                <li <?php echo ( ! $exibirAulas ) ? 'style="display: none;"' : '' ?>>
                    <h4>Escolha a aula:</h4>
                    <?php echo $selectAulas ?>
                </li>
            </ul>
        </nav>
    </header>
    <div>
        <div id="div-container-recurso-listar-datatable" style="display: none;">
        </div>
    </div>
</div>