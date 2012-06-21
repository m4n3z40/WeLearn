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
            <ul>
                <li>Criado em: <span><?php echo date('d/m/Y, à\s H:i:s', $forum->dataCriacao) ?></span></li>
                <li>Posts: <span><?php echo $forum->qtdPosts ?></span></li>
            </ul>
            <hr />
            <nav class="forum-adminpanel">
                <ul>

                    <li><?php echo anchor('/curso/forum/alterar_status/' . $forum->id,
                                          ($forum->status === WeLearn_Cursos_Foruns_StatusForum::ATIVO) ? 'Desativar' : 'Ativar',
                                         'class="a-alterarstatus-forum"') ?></li>
                    <li><?php echo anchor('/curso/forum/alterar/' . $forum->id, 'Alterar', 'class="a-alterar-forum"') ?></li>
                    <li><?php echo anchor('/curso/forum/remover/' . $forum->id, 'Remover', 'class="a-remover-forum"') ?></li>
                </ul>
            </nav>
        </td>
    </tr>
<?php endforeach; ?>