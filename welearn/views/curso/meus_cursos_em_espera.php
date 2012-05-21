<div id="curso-cursosemespera-content">
    <header>
        <hgroup>
            <h1>Minhas Incrições para Cursos Pendentes</h1>
            <h3>Aqui é onde você ficará atualizado sobre suas Inscrições para Cursos restritos!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <?php if ($haCursos): ?>
        <h4>Exibindo <em class="em-total-requisicao-inscricao"><?php echo $totalCursos ?></em> Cursos em que sua inscrição se encontra pendente.</h4>
        <ul>
            <?php foreach ($listaCursos as $curso): ?>
            <li>
                <?php echo $curso->toHTML(true) ?>
                <ul>
                    <li><?php echo anchor(
                        '/curso/aluno/cancelar_requisicao/' . $curso->id,
                        'Cancelar Requisição',
                        'class="a-cancelar-requisicao-inscricao"'
                    ) ?></li>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <h4>Não há inscrições em cursos pendentes no momento.</h4>
        <?php endif; ?>
    </div>
</div>