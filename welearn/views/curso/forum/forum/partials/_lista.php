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
            <?php echo gerar_menu_autorizado(
                array(
                    array(
                        'uri' => '/curso/forum/alterar_status/' . $forum->id,
                        'texto' => ($forum->status === WeLearn_Cursos_Foruns_StatusForum::ATIVO) ? 'Desativar' : 'Ativar',
                        'attr' => 'class="a-alterarstatus-forum"',
                        'acao' => 'forum/alterar_status',
                        'papel' => $papelUsuarioAtual
                    ),
                    array(
                        'uri' => '/curso/forum/alterar/' . $forum->id,
                        'texto' => 'Alterar',
                        'attr' => 'class="a-alterar-forum"',
                        'acao' => 'forum/alterar',
                        'papel' => $papelUsuarioAtual,
                        'autor' => $forum->criador
                    ),
                    array(
                        'uri' => '/curso/forum/remover/' . $forum->id,
                        'texto' => 'Remover',
                        'attr' => 'class="a-remover-forum"',
                        'acao' => 'forum/remover',
                        'papel' => $papelUsuarioAtual,
                        'autor' => $forum->criador
                    ),
                ),
                array('<li>','</li>'),
                array('<hr /><nav class="forum-adminpanel"><ul>','</ul></nav>')
            ) ?>
        </td>
    </tr>
<?php endforeach; ?>