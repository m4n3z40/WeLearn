<div id="dados-perfil-index-content">
    <header>
        <hgroup>
            <h1>Perfil de <?=$usuarioPerfil->nome?></h1>
            <h3>Dados Profissionais de <?=$usuarioPerfil->nome?></h3>
        </hgroup>
        <p></p>
    </header>
</div>
<?php if($dadosProfissionais instanceof WeLearn_Usuarios_DadosProfissionaisUsuario):?>
    <?if($dadosProfissionais->escolaridade == '' && $dadosProfissionais->escola == '' && $dadosProfissionais->faculdade == ''
        && $dadosProfissionais->curso == '' && $dadosProfissionais->diploma == '' && $dadosProfissionais->ano == ''
        && $dadosProfissionais->profissao == '' && @$dadosProfissionais->segmentoTrabalho->area->descricao == ''
        && @$dadosProfissionais->segmentoTrabalho->descricao == '' && $dadosProfissionais->empresa == ''
        && $dadosProfissionais->siteEmpresa == '' && $dadosProfissionais->cargo == ''
        && $dadosProfissionais->descricaoTrabalho == '' && $dadosProfissionais->habilidadesProfissionais == ''
        && $dadosProfissionais->interessesProfissionais == ''):?>
        <h3>O usuario <?=$usuarioPerfil->nome?> ainda não cadastrou dados profissionais pq apagou</h3>
    <?else:?>
    <?if($dadosProfissionais->escolaridade != '' || $dadosProfissionais->escola != '' || $dadosProfissionais->faculdade != ''
         || $dadosProfissionais->curso != '' || $dadosProfissionais->diploma != '' || $dadosProfissionais->ano != ''):?>
        <section>
            <h4>Dados Escolaridade</h4>
            <dl>
                <?if($dadosProfissionais->escolaridade != ''):?>
                <dt>Escolaridade</dt>
                <dd><?=$dadosProfissionais->escolaridade?></dd>
                <?endif;?>
                <?if($dadosProfissionais->escola != ''):?>
                <dt>Escola</dt>
                <dd><?=$dadosProfissionais->escola?></dd>
                <?endif;?>
                <?if($dadosProfissionais->faculdade != ''):?>
                <dt>Faculdade</dt>
                <dd><?=$dadosProfissionais->faculdade?></dd>
                <?endif;?>
                <?if($dadosProfissionais->curso != ''):?>
                <dt>Curso</dt>
                <dd><?=$dadosProfissionais->curso?></dd>
                <?endif;?>
                <?if($dadosProfissionais->diploma != ''):?>
                <dt>Diploma</dt>
                <dd><?=$dadosProfissionais->diploma?></dd>
                <?endif;?>
                <?if($dadosProfissionais->ano != ''):?>
                <dt>Ano</dt>
                <dd><?=$dadosProfissionais->ano?></dd>
                <?endif;?>
            </dl>
        </section>
        <?endif;?>
    <?if($dadosProfissionais->profissao != '' || @$dadosProfissionais->segmentoTrabalho->area->descricao != ''
         || @$dadosProfissionais->segmentoTrabalho->descricao != '' || $dadosProfissionais->empresa != ''
         || $dadosProfissionais->siteEmpresa != '' || $dadosProfissionais->cargo != '' || $dadosProfissionais->descricaoTrabalho != ''
         || $dadosProfissionais->habilidadesProfissionais != '' || $dadosProfissionais->interessesProfissionais != ''):?>
        <section>
            <h4>Dados Profissionais</h4>
            <dl>
                <?if($dadosProfissionais->profissao != ''):?>
                <dt>Profissão</dt>
                <dd><?=$dadosProfissionais->profissao?></dd>
                <?endif;?>
                <?if(@$dadosProfissionais->segmentoTrabalho->area->descricao != ''):?>
                <dt>Área de Trabalho</dt>
                <dd><?=$dadosProfissionais->segmentoTrabalho->area->descricao?></dd>
                <?endif;?>
                <?if(@$dadosProfissionais->segmentoTrabalho->descricao != ''):?>
                <dt>Segmento de Trabalho</dt>
                <dd><?=$dadosProfissionais->segmentoTrabalho->descricao?></dd>
                <?endif;?>
                <?if($dadosProfissionais->empresa != ''):?>
                <dt>Empresa/Organização</dt>
                <dd><?=$dadosProfissionais->empresa?></dd>
                <?endif;?>
                <?if($dadosProfissionais->siteEmpresa != ''):?>
                <dt>Site da Empresa</dt>
                <dd><?=$dadosProfissionais->siteEmpresa?></dd>
                <?endif;?>
                <?if($dadosProfissionais->cargo != ''):?>
                <dt>Cargo</dt>
                <dd><?=$dadosProfissionais->cargo?></dd>
                <?endif;?>
                <?if($dadosProfissionais->descricaoTrabalho != ''):?>
                <dt>Descrição das atividades do seu trabalho</dt>
                <dd><?=$dadosProfissionais->descricaoTrabalho?></dd>
                <?endif;?>
                <?if($dadosProfissionais->habilidadesProfissionais != ''):?>
                <dt>Habilidade Profissionais</dt>
                <dd><?=$dadosProfissionais->habilidadesProfissionais?></dd>
                <?endif;?>
                <?if($dadosProfissionais->interessesProfissionais != ''):?>
                <dt>Interesses Profissionais</dt>
                <dd><?=$dadosProfissionais->interessesProfissionais?></dd>
                <?endif;?>
            </dl>
        </section>
        <?endif;?>
    <?endif;?>
<?php else:?>
    <h3>O usuario <?=$usuarioPerfil->nome?> ainda não cadastrou dados profissionais</h3>
<?php endif;?>