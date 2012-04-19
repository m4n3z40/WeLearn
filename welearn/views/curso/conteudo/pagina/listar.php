<div id="pagina-listar-content">
    <header>
        <hgroup>
            <h1>Páginas da Aula <?php echo $aula->nroOrdem ?></h1>
            <h3>Abaixo você encontrará as páginas pertencentes à aula
                "<?php echo $aula->nome ?>"</h3>
        </hgroup>
        <div>
            Quer gerenciar as páginas de outra aula?
            <?php echo anchor('#', 'Clique aqui!', array('id' => 'a-pagina-alterar-aula')) ?>
            <ul style="display: none;" id="ul-pagina-alterar-aula">
                <li><?php echo $selectModulos ?></li>
                <li><?php echo $selectAulas ?></li>
            </ul>
        </div>
        <p>
            <?php echo anchor(
                '/curso/conteudo/pagina/criar/' . $aula->id,
                'Adicionar uma nova página',
                array('class' => 'a-adicionar-pagina')
            ) ?>
        </p>
    </header>
    <div>
    <?php if ($haPaginas): ?>
        <p>
            Sinta-se livre para mudar a ordem das páginas da maneira que preferir.
            Para isso, basta clicar e arrastar a página que quiser para posição
            de sua preferência e então clicar no botão "Salvar ordem das páginas",
            que aparecerá logo acima e abaixo da lista.
        </p>
        <p id="p-pagina-listar-qtdTotal">
            Exibindo <strong><?php echo $totalPaginas ?></strong> Página(s) -
            (Máximo permitido: <strong><?php echo PaginaDAO::MAX_PAGINAS ?></strong>)
        </p>
        <div class="div-pagina-gerenciar-posicoes">
            <button>Salvar ordem das páginas</button>
        </div>
        <ul id="ul-pagina-listar-lista" data-id-aula="<?php echo $aula->id ?>">
            <?php echo $listaPaginas ?>
        </ul>
        <div class="div-pagina-gerenciar-posicoes">
            <button>Salvar ordem das páginas</button>
        </div>
        <p>
            <?php echo anchor(
                '/curso/conteudo/pagina/criar/' . $aula->id,
                'Adicionar uma nova página',
                array('class' => 'a-adicionar-pagina')
            ) ?>
        </p>
    <?php else: ?>
        <h4>
            Nenhuma página foi adicionada nesta aula até o momento.
            <?php echo anchor(
                '/curso/conteudo/pagina/criar/' . $aula->id,
                'Adicione a primeira!',
                array('class' => 'a-adicionar-pagina')
            ) ?>
        </h4>
    <?php endif; ?>
    </div>
</div>