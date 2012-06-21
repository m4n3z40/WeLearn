<div id="enquete-exibirresultados-content">
    <header>
        <hgroup>
            <h1>Exibição de Resultados <?php echo $textoSituacao ?> da Enquete</h1>
            <h3>Abaixo o gráfico demonstrativo das proporções equivalentes
                ao número de votos que cada alternativa recebeu</h3>
        </hgroup>
        <p>
            Já viu o que queria?
            <?php echo anchor('/curso/enquete/' . $enquete->curso->id,
            'Clique aqui para voltar à lista de enquetes.') ?>
        </p>
        <ul>
            <li>Criada por: <?php echo $enquete->criador->toHTML('imagem_pequena') ?></li>
            <li>Criada em: <?php echo date('d/m/Y H:i:s', $enquete->dataCriacao) ?></li>
            <li>Fecha as votações em: <?php echo ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ?
                                                 date('d/m/Y H:i:s', $enquete->dataExpiracao) :
                                                 WeLearn_Cursos_Enquetes_SituacaoEnquete::getDescricao($enquete->situacao) ?></li>
            <li>Status: <?php echo WeLearn_Cursos_Enquetes_StatusEnquete::getDescricao($enquete->status) ?></li>
            <?php if ($linkParaVotar): ?>
            <li><?php echo anchor('/curso/enquete/exibir/' . $enquete->id, 'Participar desta enquete!') ?></li>
            <?php endif; ?>
        </ul>
        <nav id="enquete-exibir-adminpanel" class="enquete-adminpanel">
            <ul>
                <li><?php echo anchor('curso/enquete/alterar/' . $enquete->id, 'Alterar') ?></li>
                <li><?php echo anchor('curso/enquete/remover/' . $enquete->id, 'Remover',
                    array('class' => 'a-enquete-remover')) ?></li>
                <li><?php echo anchor('curso/enquete/alterar_status/' . $enquete->id,
                    ($enquete->status == WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA) ? 'Desativar' : 'Ativar',
                    array('class' => 'a-enquete-alterarstatus')) ?></li>
                <li><?php echo anchor('curso/enquete/alterar_situacao/' . $enquete->id,
                    ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ? 'Fechar' : 'Reabrir',
                    array('class' => 'a-enquete-alterarsituacao')) ?></li>
            </ul>
        </nav>
    </header>
    <div>
        <div class="barchart">
            <header>
                <h2><?php echo $enquete->questao ?></h2>
                <p>
                    Total de votos: <em><?php echo $enquete->totalVotos ?></em>
                </p>
            </header>
            <ul>
            <?php foreach ($enquete->alternativas as $alternativa): ?>
                <li>
                    <p><?php echo $alternativa->txtAlternativa ?></p>
                    <div class="barchart-bar" style="width: <?php echo $alternativa->proporcaoParcial ?>%;">
                        <p>
                            <?php echo $alternativa->proporcaoParcial ?>%
                            <span>(<?php echo $alternativa->totalVotos ?> votos)</span>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>