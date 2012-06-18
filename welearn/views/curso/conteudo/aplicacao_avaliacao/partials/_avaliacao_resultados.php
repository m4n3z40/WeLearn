<div id="resultados-avaliacao-content">
    <header>
        <hgroup>
            <h1>Avaliação: <?php echo $controleAvaliacao->avaliacao->nome ?></h1>
            <h3>Resultados da Avaliação do Módulo
                <?php echo $controleAvaliacao->avaliacao->modulo->nroOrdem ?>:
                <?php echo $controleAvaliacao->avaliacao->modulo->nome ?></h3>
        </hgroup>
        <table>
            <tr>
                <th>Qtd. de Questões</th>
                <th>Tempo Decorrido</th>
                <th>Situação</th>
                <th>Nota</th>
                <th>Realizada em</th>
            </tr>
            <tr>
                <td><em><?php echo $controleAvaliacao->avaliacao->qtdQuestoesExibir ?></em></td>
                <td><em><?php echo round($controleAvaliacao->tempoDecorrido) ?></em> min.</td>
                <td><em><?php echo WeLearn_Cursos_Avaliacoes_SituacaoAvaliacao::getDescricao( $controleAvaliacao->situacao ) ?></em></td>
                <td><em><?php echo number_format($controleAvaliacao->nota, 1, ',', '.') ?></em></td>
                <td><em><?php echo date('d/m/Y à\s H:i:s') ?></em></td>
            </tr>
        </table>
    </header>
    <div>
        <ul id="ul-resultados-avaliacao" >
            <?php $i = 0; foreach ($questoesRealizadas as $questaoRealizada): ?>
            <li id="questao-<?php echo $questaoRealizada['questao']->id ?>" class="li-questao-exibir-questao">
                <div>
                    <div class="div-questao-exibir-enunciado">
                        <h4>Questão <?php echo ++$i ?>:</h4>
                        <pre><?php echo $questaoRealizada['questao']->enunciado ?></pre>
                    </div>
                    <div class="div-questao-exibir-alternativas">
                        <h4>Alternativas:</h4>
                        <ul>
                        <?php foreach ($questaoRealizada['respostas'] as $resposta): ?>
                            <?php if ($resposta->correta): ?>
                            <li class="li-resposta-avaliacao-correta"><?php echo $resposta->txtAlternativa ?></li>
                            <?php else: ?>
                            <li class="li-resposta-avaliacao-incorreta"><?php echo $resposta->txtAlternativa ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php if ($questoesEmBranco > 0): ?>
        <div>
            <p>Você deixou <?php echo $questoesEmBranco ?> questão(ões) em branco.</p>
        </div>
        <?php endif; ?>
    </div>
</div>