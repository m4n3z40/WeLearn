<div id="requisicoes-index-content">
    <header>
        <hgroup>
            <h1>Listando Requisições de Inscrição para o Curso</h1>
            <h3>Aqui você poderá ficar atualizado sobre quem quer participar neste curso</h3>
        </hgroup>
        <p>Aqui você está vendo todas as requisições de inscrição pendentes até o momento.<br>
           Essas pessoas sendo listadas abaixo querem sua pemissão para acesar o conteúdo do curso.</p>
    </header>
    <div>
    <?php if ($haRequisicoes): ?>
        <h4>Exibindo <em class="em-qtd-requisicoes"><?php echo $qtdRequisicoes ?></em> de <em class="em-total-requisicoes"><?php echo $totalRequisicoes ?></em> Requisição(ões).</h4>
        <ul id="ul-lista-requisicoes" class="ul-grid-cursos-alunos">
            <?php echo $listaRequisicoes ?>
        </ul>
        <?php if ($haMaisPaginas): ?>
            <a href="#" id="a-paginacao-requisicoes" data-id-curso="<?php echo $idCurso ?>" data-proximo="<?php echo $idProximo ?>">Mais requisições...</a>
        <?php else: ?>
            <h4>Não há mais requisições a serem exibidas.</h4>
        <?php endif; ?>
    <?php else: ?>
        <h4>Atualmente não há requisições aguardando aprovação.</h4>
    <?php endif; ?>
    </div>
</div>