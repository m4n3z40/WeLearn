<?php foreach ($listaModulos as $modulo): ?>
<li id="<?php echo $modulo->id ?>">
    <h3><a href="#">Módulo <em><?php echo $modulo->nroOrdem ?></em>:
        <?php echo $modulo->nome ?></a></h3>
    <div>
        <div>
            <h4>Descrição:</h4>
            <p><?php echo nl2br($modulo->descricao) ?></p>
            <h4>Objetivos:</h4>
            <p><?php echo nl2br($modulo->objetivos) ?></p>
        </div>
        <footer>
            <nav class="modulo-adminpanel">
                <ul>
                    <li>
                        <?php echo anchor(
                            '/curso/conteudo/modulo/alterar/' . $modulo->id,
                            'Alterar',
                            array('class' => 'a-modulo-alterar')
                        ) ?>
                    </li>
                    <li>
                        <?php echo anchor(
                            '/curso/conteudo/modulo/remover/' . $modulo->id,
                            'Remover',
                            array('class' => 'a-modulo-remover')
                        ) ?>
                    </li>
                    <li>
                        <?php echo anchor(
                            '/curso/conteudo/avaliacao/exibir/' . $modulo->id,
                            'Gerenciar Avaliação') . ' - (';
                            echo ($modulo->existeAvaliacao) ? 'com' : 'sem';
                            echo ' avaliação)';
                        ?>
                    </li>
                    <li>
                        <?php echo anchor('/curso/conteudo/aula/'
                                              . $modulo->curso->id
                                              . '?m=' . $modulo->id,
                                          'Gerenciar Aulas')
                                          . ' - (' . $modulo->qtdTotalAulas . ' aulas)'
                        ?>
                    </li>
                    <li>
                        <?php echo anchor('/curso/conteudo/recurso/restrito/'
                                              . $modulo->curso->id
                                              . '?m=' . $modulo->id,
                                          'Gerenciar Recursos'
                        ) ?>
                    </li>
                </ul>
            </nav>
        </footer>
    </div>
</li>
<?php endforeach; ?>