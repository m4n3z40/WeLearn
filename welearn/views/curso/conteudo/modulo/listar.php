<div id="modulo-listar-content">
    <header>
        <hgroup>
            <h1>Lista de Módulos do Curso</h1>
            <h3>Abaixo você verá todos módulos contidos neste curso.</h3>
        </hgroup>
        <p>
            Quer criar um novo módulo de curso?
            <?php echo anchor('/curso/conteudo/modulo/criar/' . $idCurso, 'É por aqui!') ?>
        </p>
    </header>
    <div>
    <?php if ($haModulos): ?>
        <p>
            Sinta-se livre para mudar a ordem dos módulos da maneira que preferir.
            Para isso, basta clicar e arrastar o módulo que quiser para posição de
            sua preferência e então clicar no botão "Salvar ordem dos módulos", que
            aparecerá logo acima e abaixo da lista.
        </p>
        <div class="div-modulo-gerenciar-posicoes">
            <button class="btn-modulo-salvar-posicoes">Salvar ordem dos módulos</button>
        </div>
        <ul id="ul-modulo-listar-lista" data-id-curso="<?php echo $idCurso ?>">
            <?php echo $listaModulos ?>
        </ul>
        <div class="div-modulo-gerenciar-posicoes">
            <button class="btn-modulo-salvar-posicoes">Salvar ordem dos módulos</button>
        </div>
    <?php else: ?>
        <h4>
            Nenhum módulo de curso foi criado até o momento.
            <?php echo anchor(
                '/curso/conteudo/modulo/criar/' . $idCurso,
                'Crie o primeiro!'
            ) ?>
        </h4>
    <?php endif ?>
    </div>
</div>