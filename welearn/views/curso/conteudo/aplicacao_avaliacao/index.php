<div id="exibicao-avaliacoes-content">
    <header>
        <hgroup>
            <h1>Avaliações Disponíveis/Realizadas</h1>
            <h3>Aqui é onde você poderá acessar as avaliações disponíveis para você ou as que você já realizou.</h3>
        </hgroup>
        <p>Quer voltar para sala de aula? <?php echo anchor('/curso/conteudo/exibicao/' . $idCurso, 'Clique Aqui!') ?></p>
    </header>
    <div>
        <?php if ($haAvaliacoesDisponiveis): ?>
        <table>
            <tr>
                <th>Referente ao Módulo</th>
                <th>Nome</th>
                <th>Nota Mínima</th>
                <th>Qtd. Questões</th>
                <th>Qtd. de Tentativas</th>
                <th>Situação Atual/Ações Disponíveis</th>
            </tr>
            <?php foreach ($listaControlesAvaliacoes as $controleAvaliacao): ?>
            <tr>
                <td>Módulo <?php echo $controleAvaliacao->avaliacao->modulo->nroOrdem ?> :
                    <?php echo $controleAvaliacao->avaliacao->modulo->nome ?></td>
                <td><?php echo $controleAvaliacao->avaliacao->nome ?></td>
                <td><?php echo number_format($controleAvaliacao->avaliacao->notaMinima, 1, ',', '.') ?></td>
                <td><?php echo $controleAvaliacao->avaliacao->qtdQuestoesExibir ?></td>
                <td><?php echo $controleAvaliacao->avaliacao->qtdTentativasPermitidas === 0
                        ? 'Sem Limites'
                        : $controleAvaliacao->avaliacao->qtdTentativasPermitidas ?></td>
                <td>
                    <ul>
                        <li><strong>Situação: </strong><?php echo WeLearn_Cursos_Avaliacoes_SituacaoAvaliacao::getDescricao( $controleAvaliacao->situacao ) ?></li>
                        <?php if (
                            ( $controleAvaliacao->situacaoNaoIniciada || $controleAvaliacao->situacaoIniciada ) &&
                            $controleAvaliacao->statusLiberada
                        ): ?>
                        <li><a href="#" id="a-realizar-avaliacao" data-id-avaliacao="<?php echo $controleAvaliacao->avaliacao->id ?>">Realizar Avaliação</a></li>
                        <?php elseif(
                            ( $controleAvaliacao->situacaoAprovado || $controleAvaliacao->situacaoReprovado ) &&
                            !$controleAvaliacao->statusDesativada
                        ): ?>
                        <li><strong>Nota: </strong><?php echo number_format($controleAvaliacao->nota, 1, ',', '.') ?></li>
                        <li><strong>Finalizada em: </strong><?php echo round($controleAvaliacao->tempoDecorrido) ?> min.</li>
                        <li><strong>Realizada em: </strong><?php echo date('d/m/Y à\s H:m:i', $controleAvaliacao->dataAplicacao) ?></li>
                        <li><a href="#" id="a-exibir-resultados-avaliacao" data-id-avaliacao="<?php echo $controleAvaliacao->avaliacao->id ?>">Exibir Resultados</a></li>
                        <?php else: ?>
                        <li>Infelizmente você não poderá mais continuar o curso porque falhou muitas vezes nesta avaliação :(</li>
                        <?php endif; ?>
                    </ul>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <h4>Não há nenhuma avaliação dispovível no momento.</h4>
        <?php endif; ?>
    </div>
</div>