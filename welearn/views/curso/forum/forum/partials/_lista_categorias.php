<?php foreach ($listaCategorias as $categoria): ?>
<tr>
    <td>
        <div>
            <h4><?php echo anchor('/curso/forum/listar/' . $categoria->id, $categoria->nome) ?></h4>
            <p><?php echo $categoria->descricao ?></p>
            <?php echo gerar_menu_autorizado(array(
                array(
                    'uri' => '/curso/forum/criar/' . $categoria->id,
                    'texto' => 'Criar fórum nesta categoria',
                    'acao' => 'categoria/criar',
                    'papel' => $papelUsuarioAtual
                )
            ), array('<p>', '</p>')) ?>
        </div>
    </td>
    <td>
        <div>
            <ul>
                <li>Fóruns nesta categoria: <span><?php echo $categoria->qtdForuns ?></span></li>
                <li>Criada em: <span><?php echo date('d/m/Y, H:i:s', $categoria->dataCriacao) ?></span></li>
            </ul>
        </div>
    </td>
</tr>
<?php endforeach ?>
