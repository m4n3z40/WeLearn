<?php foreach ($listaAulas as $aula): ?>
<li id="<?php echo $aula->id ?>">
    <h3><a href="#">Aula <em><?php echo $aula->nroOrdem ?></em>:
        <?php echo $aula->nome ?></a></h3>
    <div>
        <header>
            <nav class="aula-adminpanel">
                <ul>
                    <li>
                        <?php echo anchor(
                            '/curso/conteudo/aula/alterar/' . $aula->id,
                            'Alterar',
                            array('class' => 'a-aula-alterar')
                        ) ?>
                    </li>
                    <li>
                        <?php echo anchor(
                            '/curso/conteudo/aula/remover/' . $aula->id,
                            'Remover',
                            array('class' => 'a-aula-remover')
                        ) ?>
                    </li>
                </ul>
            </nav>
        </header>
        <div>
            <h4>Descrição:</h4>
            <p><?php echo nl2br($aula->descricao) ?></p>
        </div>
        <footer>
            <nav>
                <ul>
                    <li>
                        <?php echo anchor('curso/conteudo/aula/exibir/' . $aula->id,
                                          'Visualizar',
                                          array('class' => 'button')) ?>
                    </li>
                    <li>
                        <?php echo anchor('/curso/conteudo/pagina/' . $aula->id,
                                          'Gerenciar Páginas',
                                          array('class' => 'button')) ?>
                    </li>
                    <li>
                        <?php echo anchor('/curso/conteudo/recurso/restrito/'
                                              . $aula->modulo->curso->id
                                              . '?a=' . $aula->id,
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