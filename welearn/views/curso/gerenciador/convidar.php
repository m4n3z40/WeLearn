<div id="gerenciador-convidar-content">
    <header>
        <hgroup>
            <h1>Convide Usuários para ajudá-lo a Gerenciar o Curso</h1>
            <h3>Busque abaixo os usuários, os adicione os escolhidos na lista
                de convites, confirme e espere a decisão deles!</h3>
        </hgroup>
        <p>Volte para lista de convites pendentes
            <?php echo anchor('/curso/gerenciador/convites/' . $idCurso, 'clicando aqui!') ?></p>
    </header>
    <div>
        <div>
            <h3>
                Lista de Convites
            </h3>
            <ul id="ul-lista-usuarios-convidar" data-id-curso="<?php echo $idCurso ?>" data-lista-convite="">
                <li>Não há convites, por enquanto.</li>
            </ul>
            <a href="#" style="display: none;" id="a-confirmar-usuarios-convidar" class="button">Confirmar!</a>
        </div>
        <div>
            <h3><label for="txt-termo">Buscar Usuários</label></h3>
            <?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
            <input type="text" name="termo" id="txt-termo" placeholder="Buscar">
            <?php echo form_close() ?>
            <ul id="ul-lista-usuarios-resultado-busca">
                <li>Não há resultados para exibir.</li>
            </ul>
            <a href="#" style="display: none;" id="a-paginacao-usuarios-convidar" data-proximo="0">Mais resultados...</a>
        </div>
    </div>
</div>