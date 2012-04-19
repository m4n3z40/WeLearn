<?php foreach ($listaPaginas as $pagina): ?>
<li id="<?php echo $pagina->id ?>">
    <h4>Página <em><?php echo $pagina->nroOrdem ?></em></h4>
    <p>"<?php echo $pagina->nome ?>"</p>
    <nav>
        <ul>
            <li><?php echo anchor(
                '/curso/conteudo/pagina/exibir/' . $pagina->id,
                'Visualizar',
                'class="a-visualizar-pagina"'
            ) ?></li>
            <li><?php echo anchor(
                '/curso/conteudo/pagina/alterar/' . $pagina->id,
                'Editar',
                'class="a-editar-pagina"'
            ) ?></li>
            <li><?php echo anchor(
                '/curso/conteudo/pagina/remover/' . $pagina->id,
                'Remover',
                'class="a-remover-pagina"'
            ) ?></li>
        </ul>
    </nav>
</li>
<?php endforeach; ?>