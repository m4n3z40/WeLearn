<section id="curso-left-bar" class="inner-sidebar-container inner-sidebar-container-left">
<?php echo gerar_menu_autorizado(
    array(
        array(
            'uri' => '/curso/' . $idCurso,
            'texto' => 'Home',
            'acao' => 'curso/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/conteudo/exibicao/' . $idCurso,
            'texto' => 'Sala de Aula',
            'acao' => 'exibicao/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/aluno/' . $idCurso,
            'texto' => 'Alunos',
            'acao' => 'aluno/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/enquete/' . $idCurso,
            'texto' => 'Enquetes',
            'acao' => 'enquete/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/forum/' . $idCurso,
            'texto' => 'Fóruns',
            'acao' => 'forum/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/gerenciador/' . $idCurso,
            'texto' => 'Gerenciadores',
            'acao' => 'gerenciador/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/conteudo/' . $idCurso,
            'texto' => 'Gerenciamento de Conteúdo',
            'acao' => 'conteudo/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/certificado/' . $idCurso,
            'texto' => 'Gerenciamento de Certificados',
            'acao' => 'certificado/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/review/' . $idCurso,
            'texto' => 'Reputação do Curso',
            'acao' => 'review/index',
            'papel' => $papelUsuarioAtual
        ),
        array(
            'uri' => '/curso/configurar/' . $idCurso,
            'texto' => 'Configurações do Curso',
            'acao' => 'curso/configurar',
            'papel' => $papelUsuarioAtual
        ),
    ),
    array('<li>','</li>'),
    array('<nav><h3>Principal</h3><ul>','</ul></nav>')
) ?>
    <hr>
    <div id="curso-left-bar-contexto">
        <?php if (! empty($menuContexto)): ?>
        <nav id="curso-left-bar-contexto-menu">
            <?php echo $menuContexto; ?>
        </nav>
        <hr>
        <?php endif ?>
        <?php if (! empty($widgetsContexto)): ?>
        <section id="curso-left-bar-contexto-widgets">
            <?php foreach ($widgetsContexto as $widget): ?>
                <?php echo $widget ?>
                <hr class="curso-widget-separator">
            <?php endforeach ?>
        </section>
        <?php endif ?>
    </div>
</section>