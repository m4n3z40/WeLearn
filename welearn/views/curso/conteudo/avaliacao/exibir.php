<div id="avaliacao-exibir-content">
    <header>
        <hgroup>
            <h1>Avaliação do Módulo <?php echo $modulo->nroOrdem ?></h1>
            <h3>Aqui você pode gerenciar a avaliação do módulo
                <em>"<?php echo $modulo->nome ?>"</em></h3>
        </hgroup>
        <p>
            Não queria estar aqui? <?php echo anchor('/curso/conteudo/avaliacao/'
                                                         . $modulo->curso->id,
                                                     'Clique aqui para voltar para index de Avaliações') ?>
        </p>
    </header>
    <div>
    <?php if ($modulo->existeAvaliacao): ?>
        <h3><?php echo $avaliacao->nome ?></h3>
        <nav>
            <ul>
                <li><?php echo anchor('/curso/conteudo/avaliacao/alterar/' . $avaliacao->id,
                                      'Alterar Dados desta Avaliação',
                                      'class="a-alterar-avaliacao"') ?></li>
                <li><?php echo anchor('/curso/conteudo/avaliacao/remover/' . $avaliacao->id,
                                      'Remover Avaliação deste Módulo',
                                      'class="a-remover-avaliacao"')?></li>
            </ul>
        </nav>
        <div>
            <h4>Informações da Avaliação</h4>
            <table>
                <tr>
                    <th>Nota Mínima</th>
                    <th>Tempo Máximo de Duração</th>
                    <th>Qtd. de Tentativas Permitidas</th>
                    <th>Qtd. de Questões</th>
                    <th>Qtd. de Questões Aplicadas</th>
                </tr>
                <tr>
                    <td><?php echo $avaliacao->notaMinima ?></td>
                    <td><?php echo $avaliacao->tempoDuracaoMax ?></td>
                    <td><?php echo $avaliacao->qtdTentativasPermitidas ?></td>
                    <td><?php echo $avaliacao->qtdQuestoes ?></td>
                    <td>
                        <?php echo $avaliacao->qtdQuestoesExibir ?>
                        <button id="btn-alterar-qtdquestoesexibir">Alterar</button>
                    </td>
                </tr>
            </table>
        </div>
        <hr>
        <div>
            <h4>Questões da Avaliação</h4>
            <nav>
                <ul>
                    <li><?php echo anchor('/curso/conteudo/avaliacao/adicionar_questao/' . $avaliacao->id,
                                          'Adicionar uma Questão',
                                          'class="a-adicionar-questao"') ?></li>
                </ul>
            </nav>
        <?php if ($avaliacao->qtdQuestoes <= 0): ?>
            <h4>Esta avaliação ainda não contem nenhuma questão.</h4>
        <?php else: ?>

        <?php endif; ?>
        </div>
    <?php else: ?>
        <h4>Não há uma avaliação vinculada a este módulo até o momento.
            <?php echo anchor('/curso/conteudo/avaliacao/criar/' . $modulo->id,
                              '<br>Clique aqui para criar uma avaliação para este módulo') ?></h4>
    <?php endif; ?>
    </div>
    <footer>
    </footer>
</div>