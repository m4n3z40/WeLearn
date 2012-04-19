<div id="aula-index-content">
    <header>
        <hgroup>
            <h1>Aulas do Curso</h1>
            <h3>Aqui é onde você encontra/gerencia as aulas existentes no curso.</h3>
        </hgroup>
        <p>
            Escolha um módulo abaixo para exibir as aulas respectivas a ele.
        </p>
    </header>
    <div>
        <?php if ($haModulos): ?>
        <h4>Exibindo <?php echo $totalModulos ?> módulos.</h4>
        <ul>
            <?php foreach ($listaModulos as $modulo): ?>
            <li><?php echo anchor('/curso/conteudo/aula/listar/' . $modulo->id,
                'Módulo ' . $modulo->nroOrdem . ': ' . $modulo->nome)
                . ' - (' . $modulo->qtdTotalAulas . ' aulas)' ?></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4>Ainda não há nenhum módulo cadastrado neste curso,
            <?php echo anchor('/curso/conteudo/modulo/' . $idCurso, 'Clique aqui parar gerenciar os módulos') ?></h4>
        <?php endif; ?>
    </div>
    <footer>
        <?php echo anchor('/curso/conteudo/modulo/' . $idCurso, 'Gerenciar Módulos') ?>
    </footer>
</div>