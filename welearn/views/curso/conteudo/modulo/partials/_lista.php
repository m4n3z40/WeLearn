<?php foreach ($listaModulos as $modulo): ?>
<li id="<?php echo $modulo->id ?>">
    <h3><a href="#">Módulo <em><?php echo $modulo->nroOrdem ?></em>:
        <?php echo $modulo->nome ?></a></h3>
    <div>
        <header>
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
                </ul>
            </nav>
        </header>
        <div>
            <h4>Descrição:</h4>
            <p><?php echo nl2br($modulo->descricao) ?></p>
            <h4>Objetivos:</h4>
            <p><?php echo nl2br($modulo->objetivos) ?></p>
        </div>
        <footer>
            <nav>
                <ul>
                    <li>
                        <?php echo anchor('/curso/conteudo/aula/'
                                              . $modulo->curso->id
                                              . '?m=' . $modulo->id,
                                          'Gerenciar Aulas ('
                                              . $modulo->qtdTotalAulas . ')',
                                          array('class' => 'button')
                        ) ?>
                    </li>
                    <li>
                        <?php echo anchor('/curso/conteudo/recurso/restrito/'
                                              . $modulo->curso->id
                                              . '?m=' . $modulo->id,
                                          'Gerenciar Recursos',
                                          array('class' => 'button')
                        ) ?>
                    </li>
                </ul>
            </nav>
        </footer>
    </div>
</li>
<?php endforeach; ?>