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
        <?php echo gerar_menu_autorizado(
            array(
                array(
                    'uri' => 'curso/enquete/alterar/' . $enquete->id,
                    'texto' => 'Alterar',
                    'autor' => $enquete->criador
                ),
                array(
                    'uri' => 'curso/enquete/remover/' . $enquete->id,
                    'texto' => 'Remover',
                    'attr' => 'class="a-enquete-remover"',
                    'acao' => 'enquete/remover',
                    'papel' => $papelUsuarioAtual,
                    'autor' => $enquete->criador
                ),
                array(
                    'uri' => 'curso/enquete/alterar_status/' . $enquete->id,
                    'texto' => ($enquete->status == WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA) ? 'Desativar' : 'Ativar',
                    'attr' => 'class="a-enquete-alterarstatus"',
                    'acao' => 'enquete/alterar_status',
                    'papel' => $papelUsuarioAtual
                ),
                array(
                    'uri' => 'curso/enquete/alterar_situacao/' . $enquete->id,
                    'texto' => ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ? 'Fechar' : 'Reabrir',
                    'attr' => 'class="a-enquete-alterarsituacao"',
                    'acao' => 'enquete/remover',
                    'papel' => $papelUsuarioAtual
                )
            ),
            array('<li>','</li>'),
            array('<nav id="enquete-exibir-adminpanel" class="enquete-adminpanel"><ul>','</ul></nav>')
        ) ?>
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