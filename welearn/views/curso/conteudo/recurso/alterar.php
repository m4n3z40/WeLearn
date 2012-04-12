<div id="recurso-alterar-content">
    <header>
        <hgroup>
            <h1>Alterar um Recurso Extra</h1>
            <h3>Preencha abaixo os campos com os dados
                necessários para alteração do recurso.</h3>
        </hgroup>
        <p>
            Algumas informações do recurso não podem ser alteradas, como por exemplo
            o arquivo para download.
            <br>
            Caso queria modificar o arquivo, terá que remover este recurso e criar um
            novo.
         </p>
        <p>
            Não queria estar aqui?
            <?php echo anchor('/curso/conteudo/recurso/' . $idCurso,
                              'Volte para a index de recursos.') ?>
        </p>
    </header>
    <div>
        <?php echo form_open($formAction, $extraOpenForm, $formHidden) ?>
        <fieldset>
            <legend>Informações alteráveis:</legend>
            <dl>
                <dt><label for="txt-nome">Nome do Recurso:</label></dt>
                <dd>
                    <input type="text"
                           name="nome"
                           id="txt-nome"
                           value="<?php echo $recurso->nome ?>">
                </dd>
                <dt><label for="txt-descricao">Descrição:</label></dt>
                <dd>
                    <textarea name="descricao"
                              id="txt-descricao"
                              cols="60"
                              rows="10"><?php echo $recurso->descricao ?></textarea>
                </dd>
            </dl>
        </fieldset>
        <fieldset>
            <legend>Informações não alteráveis</legend>
            <dl>
                <dt>Informações do Arquivo:</dt>
                <dd><?php echo $recurso ?></dd>
                <dt>Tipo do Recurso</dt>
                <dd><?php echo WeLearn_Cursos_Recursos_TipoRecurso::getDescricao($recurso->tipo) ?></dd>
            <?php if ($recurso->tipo == WeLearn_Cursos_Recursos_TipoRecurso::RESTRITO): ?>
                <dt>Pertence à aula:</dt>
                <dd><?php echo $recurso->aula->nome ?></dd>
            <?php endif; ?>
                <dt>Enviado por: </dt>
                <dd><?php echo anchor('/usuario/' . $recurso->criador->id,
                                      $recurso->criador->nome) ?></dd>
                <dt>Enviado em: </dt>
                <dd><?php echo date('d/m/Y H:i:s', $recurso->dataInclusao) ?></dd>
            </dl>
        </fieldset>
        <button type="submit" id='btn-form-recurso'>Salvar!</button>
        <?php echo form_close() ?>
    </div>
</div>