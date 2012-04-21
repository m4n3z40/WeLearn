<div id="avaliacao-criar-content">
    <header>
        <hgroup>
            <h1>Alterar avaliação do Módulo <?php echo $avaliacao->modulo->nroOrdem ?></h1>
            <h3>Preencha abaixo os dados necessários para alteração da avaliação
                <em>"<?php echo $avaliacao->nome ?>"</em></h3>
        </hgroup>
        <p>
            Não queria estar aqui? <?php echo anchor('/curso/conteudo/avaliacao/exibir/' . $avaliacao->modulo->id,
                                             'Clique aqui para voltar ao gerenciamento desta Avaliação') ?>
            <br><br>
            Ou <?php echo anchor('/curso/conteudo/avaliacao/'
                                 . $avaliacao->modulo->curso->id,
                             'Clique aqui para voltar para index de Avaliações') ?>
        </p>
    </header>
    <div>
        <?php echo $form; ?>
    </div>
    <footer>
    </footer>
</div>