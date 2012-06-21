<div id="avaliacao-index-content">
    <header>
        <hgroup>
            <h1>Avaliações do Curso</h1>
            <h3>Aqui é onde você encontra/gerencia as avaliações/testes existentes no curso.</h3>
        </hgroup>
        <p>
            As avaliações servem para "monitorar" o progresso dos alunos no curso.
            <br>
            Avaliações são organizadas por módulos, todo módulo pode ou não conter
            <strong>UMA</strong> avaliação.
            <br>
            Caso um módulo contenha uma avaliação, os alunos <strong>NÃO</strong>
            poderão passar para o próximo até que tenham alcançado ou ultrapassado
            a nota mínima da avaliação deste módulo.
            <br><br>
            Para gerenciar a avaliação de um módulo, clique no módulo desejado na
            lista abaixo.
        </p>
    </header>
    <div>
        <?php if ($haModulos): ?>
        <h4>Exibindo <?php echo $totalModulos ?> módulos.</h4>
        <ul class="ul-lista-modulos-simples">
            <?php foreach ($listaModulos as $modulo): ?>
            <li><?php
                echo anchor('/curso/conteudo/avaliacao/exibir/' . $modulo->id,
                'Módulo ' . $modulo->nroOrdem . ': ' . $modulo->nome) . ' - (';
                echo ($modulo->existeAvaliacao) ? 'com' : 'sem';
                echo ' avaliação)';
                ?></li>
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