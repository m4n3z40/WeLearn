<div id="enquete-exibir-content">
    <header>
        <hgroup>
            <h1>Exibição de Enquete</h1>
            <h3>Escolha a alternativa desejada e depois confirme sua participação</h3>
        </hgroup>
        <p>Não é aqui que queria estar? <?php echo anchor('/curso/enquete/' . $enquete->curso->id, 'Volte para lista de enquetes.') ?></p>
        <ul>
            <li>Criada por: <?php echo anchor('usuario/' . $enquete->criador->id, $enquete->criador->nome) ?></li>
            <li>Criada em: <?php echo date('d/m/Y H:i:s', $enquete->dataCriacao) ?></li>
            <li>Fecha as votações em: <?php echo ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ?
                                                 date('d/m/Y H:i:s', $enquete->dataExpiracao) : 'Enquete fechada' ?></li>
            <li>Total de participações: <?php echo $enquete->totalVotos ?></li>
        </ul>
    </header>
    <div>
        <nav id="enquete-listar-adminpanel">
            <ul>
                <li><?php echo anchor('curso/enquete/alterar/' . $enquete->id, 'Alterar') ?></li>
                <li><?php echo anchor('curso/enquete/remover/' . $enquete->id, 'Remover',
                    array('id' => 'a-enquete-remover')) ?></li>
                <li><?php echo anchor('curso/enquete/alterar_status/' . $enquete->id,
                    ($enquete->status == WeLearn_Cursos_Enquetes_StatusEnquete::ATIVA) ? 'Desativar' : 'Ativar',
                    array('id' => 'a-enquete-alterarstatus')) ?></li>
                <li><?php echo anchor('curso/enquete/alterar_situacao/' . $enquete->id,
                    ($enquete->situacao == WeLearn_Cursos_Enquetes_SituacaoEnquete::ABERTA) ? 'Fechar' : 'Reabrir',
                    array('id' => 'a-enquete-alterarsituacao')) ?></li>
            </ul>
        </nav>
        <?php echo form_open($formAction, $extraOpenForm, $formHidden); ?>
            <h2><?php echo $enquete->questao ?></h2>
            <ul id="ul-enquete-alternativas">
            <?php foreach ($enquete->alternativas as $alternativa): ?>
                <li>
                    <input type="radio"
                           name="alternativa_escolhida"
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