<?php foreach ($listaCategorias as $categoria): ?>
<tr>
    <td>
        <div>
            <h4><?php echo anchor('curso/forum/listar/' . $categoria->id, $categoria->nome) ?></h4>
            <p><?php echo $categoria->descricao ?></p>
            <footer>
                <p>
                    <?php echo anchor('curso/forum/criar/' . $categoria->id, 'Criar fórum nesta categoria') ?>
                </p>
            </footer>
        </div>
    </td>
    <td>
        <div>
            <p>
                Fóruns nesta categoria: <span><?php echo $categoria->qtdForuns ?></span><br>
                Criada em: <span><?php echo date('d/m/Y, H:i:s', $categoria->dataCriacao) ?></span>
            </p>
        </div>
    </td>
</tr>
<?php endforeach ?>
