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
        <input type="hidden" id="hdn-id-avaliacao" name="avaliacaoId" value="<?php echo $avaliacao->id ?>">
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
            <?php if ($avaliacao->qtdQuestoesExibir <= 0): ?>
            <p>Obs.: <strong>Esta avaliação ainda não está ativa!</strong>
                <br>
                Para ativá-la altere a <em>"Qtd. de Questões Aplicadas"</em> para um
                valor maior que "0".</p>
            <?php endif; ?>
            <table>
                <tr>
                    <th>Nota Mínima</th>
                    <th>Tempo Máximo de Duração</th>
                    <th>Qtd. de Tentativas Permitidas</th>
                    <th>Qtd. de Questões</th>
                    <th>Qtd. de Questões Aplicadas</th>
                </tr>
                <tr>
                    <td><?php echo number_format($avaliacao->notaMinima, 1, ',', '.') ?></td>
                    <td><?php echo $avaliacao->tempoDuracaoMax ?></td>
                    <td><?php echo ($avaliacao->qtdTentativasPermitidas == 0)
                                    ? 'Sem limites'
                                    : $avaliacao->qtdTentativasPermitidas . ' Tentativa(s)' ?></td>
                    <td class="avaliacao-qtd-questoes"><?php echo $avaliacao->qtdQuestoes ?></td>
                    <td>
                        <span><?php echo $avaliacao->qtdQuestoesExibir ?></span>
                        <button id="btn-alterar-qtdquestoesexibir" data-acao="alterar">Alterar</button>
                    </td>
                </tr>
            </table>
        </div>
        <hr>
        <div>
            <h4>Questões da Avaliação</h4>
            <p>Não se preocupe com a ordem das questões, elas serão "embaralhadas"
                na aplicação da avaliação.</p>
            <nav>
                <ul>
                    <li><?php echo anchor('/curso/conteudo/avaliacao/adicionar_questao/' . $avaliacao->id,
                                          'Adicionar uma Questão',
                                          'class="a-adicionar-questao"') ?></li>
                </ul>
            </nav>
            <h4>Exibindo <em class="avaliacao-qtd-questoes"><?php echo $avaliacao->qtdQuestoes ?>
            </em> questões. (Máx. <?php echo $maxQuestoes ?>)</h4>
        <?php if ($avaliacao->qtdQuestoes <= 0): ?>
            <h4 id="h4-questao-listar-semquestao">Esta avaliação ainda não contem nenhuma questão.</h4>
        <?php else: ?>
            <table id="tbl-questao-listar-datatable">
                <tr>
                    <th>Enunciado</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php echo $listaQuestoes ?>
            </table>
        <?php endif; ?>
            <nav>
                <ul>
                    <li><?php echo anchor('/curso/conteudo/avaliacao/adicionar_questao/' . $avaliacao->id,
                                          'Adicionar uma Questão',
                                          'class="a-adicionar-questao"') ?></li>
                </ul>
            </nav>
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