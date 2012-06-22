<div id="enquete-exibir-content">
    <header>
        <hgroup>
            <h1>Exibição de Enquete</h1>
            <h3>Escolha a alternativa desejada e depois confirme sua participação</h3>
        </hgroup>
        <p>Não é aqui que queria estar? <?php echo anchor('/curso/enquete/' . $enquete->curso->id, 'Volte para lista de enquetes.') ?></p>
        <ul>
            <li>Criada por: <?php echo $enquete->criador->toHTML('imagem_pequena') ?></li>
            <li>Criada em: <?php echo date('d/m/Y H:i:s', $enquete->dataCriacao) ?></li>
            <li>Fecha as votações em: <?php echo ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ?
                                                 date('d/m/Y H:i:s', $enquete->dataExpiracao) :
                                                 WeLearn_Cursos_Enquetes_SituacaoEnquete::getDescricao($enquete->situacao) ?></li>
            <li>Status: <?php echo WeLearn_Cursos_Enquetes_StatusEnquete::getDescricao($enquete->status) ?></li>
            <li>Total de participações: <?php echo $enquete->totalVotos ?></li>
            <li><?php echo anchor('/curso/enquete/exibir_resultados/' . $enquete->id, 'Visualizar resultados!') ?></li>
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
        <?php echo form_open($formAction, $extraOpenForm, $formHidden); ?>
            <h2><?php echo $enquete->questao ?></h2>
            <ul id="ul-enquete-alternativas" class="selectable-radios">
            <?php foreach ($enquete->alternativas as $alternativa): ?>
                <li>
                    <input type="radio"
                           name="alternativaEscolhida"
                           id="<?php echo $alternativa->id ?>"
                           value="<?php echo $alternativa->id ?>">
                    <label for="<?php echo $alternativa->id ?>"><?php echo $alternativa->txtAlternativa ?></label>
                </li>
            <?php endforeach; ?>
            </ul>
            <button type="submit" id="btn-votar-enquete">Confirmar!</button>
        <?php echo form_close() ?>
    </div>
</div>