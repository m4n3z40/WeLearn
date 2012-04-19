<div id="avaliacao-criar-content">
    <header>
        <hgroup>
            <h1>Criar avaliação do Módulo <?php echo $modulo->nroOrdem ?></h1>
            <h3>Preencha abaixo os dados necessários para criação da avaliação do módulo
                <em>"<?php echo $modulo->nome ?>"</em></h3>
        </hgroup>
        <p>
            Não queria estar aqui? <?php echo anchor('/curso/conteudo/avaliacao/'
                                                         . $modulo->curso->id,
                                                     'Clique aqui para voltar para index de Avaliações') ?>
        </p>
    </header>
    <div>
        <?php if ($modulo->existeAvaliacao): ?>
        <!--TODO: Implementar exibição de avaliacao. -->
        <?php else: ?>
        <?php echo $form; ?>
        <?php endif; ?>
    </div>
    <footer>
    </footer>
</div>