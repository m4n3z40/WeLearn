<?php foreach($listaForuns as $forum): ?>
    <tr>
        <td>
            <div>
                <h4><?php echo anchor('curso/forum/post/listar/'. $forum->id, $forum->titulo) ?></h4>
                <p><?php echo $forum->descricao ?></p>
                <footer>
                    Criador: <?php echo $forum->criador->toHTML('somente_link') ?><br/>
                </footer>
            </div>
        </td>
        <td>
            <p>
                Criado em: <span><?php echo date('d/m/Y, à\s H:i:s', $forum->dataCriacao) ?></span><br/>
                Posts: <span><?php echo $forum->qtdPosts ?></span><br/>
            </p>
            <hr />
            <ul>

                <li><?php echo anchor('/curso/forum/alterar_status/' . $forum->id,
                                      ($forum->status === WeLearn_Cursos_Foruns_StatusForum::ATIVO) ? 'Desativar' : 'Ativar',
                                     'class="a-alterarstatus-forum"') ?></li>
                <li><?php echo anchor('/curso/forum/alterar/' . $forum->id, 'Alterar', 'class="a-alterar-forum"') ?></li>
                <li><?php echo anchor('/curso/forum/remover/' . $forum->id, 'Remover', 'class="a-remover-forum"') ?></li>
            </ul>
        </td>
    </tr>
<?php endforeach; ?>