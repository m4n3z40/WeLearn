
<div id="dados-perfil-index-content">
    <header>
        <hgroup>
            <h1>Perfil de: <?=$usuarioPerfil->nome?></h1>
            <h3>Dados Profissionais de: <?=$usuarioPerfil->nome?>:</h3>
        </hgroup>
        <p></p>
    </header>
</div>
<?php if(!$possuiDadosProfissionais):?>
    <h3>O usuario <?=$usuarioPerfil->id?> ainda não cadastrou dados profissionais</h3>
<?php else:?>
    <section>
        <h4>Dados Escolaridade</h4>
        <dl>
            <dt>Escolaridade</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->escolaridade?></dd>
            <dt>Escola</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->escola?></dd>
            <dt>Faculdade</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->faculdade?></dd>
            <dt>Curso</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->curso?></dd>
            <dt>Diploma</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->diploma?></dd>
            <dt>Ano</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->ano?></dd>
        </dl>
    </section>
    <section>
        <h4>Dados Profissionais</h4>
        <dl>
            <dt>Profissão</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->profissao?></dd>
            <dt>Área de Trabalho</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->segmentoTrabalho->area->descricao?></dd>
            <dt>Segmento de Trabalho</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->segmentoTrabalho->descricao?></dd>
            <dt>Empresa/Organização</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->empresa?></dd>
            <dt>Site da Empresa</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->siteEmpresa?></dd>
            <dt>Cargo</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->cargo?></dd>
            <dt>Descrição das atividades do seu trabalho</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->descricaoTrabalho?></dd>
            <dt>Habilidade Profissionais</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->habilidadesProfissionais?></dd>
            <dt>Interesses Profissionais</dt>
            <dd><?=$usuarioPerfil->dadosProfissionais->interessesProfissionais?></dd>
        </dl>
    </section>
<?php endif;?>