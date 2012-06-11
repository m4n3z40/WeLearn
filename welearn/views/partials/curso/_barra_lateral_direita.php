<section id="curso-right-bar" class="inner-sidebar-container inner-sidebar-container-right">
    <header>
        <figure>
            <?php echo anchor(
                '/curso/' . $idCurso,
                "<img src=\"{$imagemUrl}\" alt=\"{$descricao}\" /><figcaption>{$nome}</figcaption>",
                "title=\"{$descricao}\""
            ) ?>
        </figure>
        <div>
        <?php if ( $tipoVinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::USUARIO ): ?>
            <?php echo anchor(
                '/curso/inscrever/' . $idCurso,
                'Inscrever-se no Curso',
                'title="Inscrever-se no Curso" id="a-curso-inscrever" class="button"'
            ) ?>
        <?php elseif( $tipoVinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO_INSCRICAO_PENDENTE ): ?>
            <span>Sua inscrição está sendo avaliada pelos gerenciadores.</span>
        <?php elseif( $tipoVinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_CONVITE_PENDENTE ): ?>
            <span>Você foi convidado à gerenciar este curso. Verifique a seção "Convites para Gerenciamento" na área "Meus Cursos". </span>
        <?php elseif( $tipoVinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::ALUNO ): ?>
            <?php echo anchor(
                '/curso/sair/' . $idCurso,
                'Sair do Curso',
                'title="Sair do Curso" id="a-curso-desvincular" class="button"'
            ) ?>
        <?php elseif( $tipoVinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_AUXILIAR ): ?>
            <?php echo anchor(
                '/curso/sair/' . $idCurso,
                'Abandonar Gerência do Curso',
                'title="Sair do Curso" id="a-curso-desvincular" class="button"'
            ) ?>
        <?php elseif( $tipoVinculo === WeLearn_Usuarios_Autorizacao_NivelAcesso::GERENCIADOR_PRINCIPAL ): ?>
            <span>Você é o criador deste curso! Longa vida à <?php echo $nomeCriador ?>!</span>
        <?php endif; ?>
        </div>
    </header>
    <div id="curso-right-bar-contexto">
        <?php if (! empty($menuContexto)): ?>
        <nav id="curso-right-bar-contexto-menu">
            <?php echo $menuContexto; ?>
        </nav>
        <hr>
        <?php endif ?>
        <?php if (! empty($widgetsContexto)): ?>
        <section id="curso-right-bar-contexto-widgets">
            <?php foreach ($widgetsContexto as $widget): ?>
                <?php echo $widget ?>
                <hr class="curso-widget-separator">
            <?php endforeach ?>
        </section>
        <?php endif ?>
    </div>
</section>