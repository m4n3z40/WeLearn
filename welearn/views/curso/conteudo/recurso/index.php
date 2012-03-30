<div id="recurso-index-content">
    <header>
        <hgroup>
            <h1>Recursos Extras do Curso</h1>
            <h3>Aqui é onde você encontra/gerencia os recursos
                extras contidos neste curso.</h3>
        </hgroup>
        <div>
            <p>
                Existem dois tipos de recursos extras, são eles:
                <strong>"Recursos Gerais"</strong> e <strong>"Recursos Restritos".</strong>
            </p>
            <ul>
                <li>
                    Os <strong>"Recursos Gerais"</strong> são acessíveis por
                    qualquer usuário inscrito no curso.
                </li>
                <li>
                    Os <strong>"Recursos Restritos"</strong> são acessíveis somente por aqueles
                    alunos que já passaram pela aula à que o recurso pertence.
                </li>
            </ul>
            <p>
                Escolha sua opção abaixo.
            </p>
        </div>
    </header>
    <div>
        <ul>
            <li>
                <?php echo anchor('/curso/conteudo/recurso/geral/' . $idCurso,
                                  'Recursos Gerais',
                                  array('class'=>'button')) ?>
            </li>
            <li>
                <?php echo anchor('/curso/conteudo/recurso/restrito/' . $idCurso,
                                  'Recursos Restritos',
                                  array('class'=>'button')) ?>
                <div id="div-recurso-select-modulos"
                     <?php echo empty($listaModulos) ? 'style="display:none;"' : '' ?>
                    >
                    <?php echo form_dropdown('modulos',
                                             $listaModulos,
                                             $moduloSelecionado,
                                             'id="slt-recurso-modulos"') ?>
                </div>
                <div id="div-recurso-select-aulas"
                     <?php echo empty($listaAulas) ? 'style="display:none;"' : '' ?>
                    >
                    <?php echo form_dropdown('aulas',
                                             $listaAulas,
                                             '',
                                             'id="slt-recurso-aulas"') ?>
                </div>
            </li>
        </ul>
    </div>
</div>