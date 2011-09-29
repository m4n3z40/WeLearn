<div>
    <header>
        <hgroup>
            <h1>Sugestões de Cursos</h1>
            <h3>Cursos que possívelmente não existem no serviço e interessam aos usuários</h3>
        </hgroup>
    </header>
    <div>
        <header>
            <h4>Filtros</h4>
            <nav id="tab-filtro">
                <ul>
                    <li><a href="<?php echo site_url('/curso/sugestao/listar?f=new') ?>">Mais Recentes</a></li>
                    <li><a href="<?php echo site_url('/curso/sugestao/listar?f=pop') ?>">Populares</a></li>
                    <li><a href="#form-segmentos">Por área ou segmento</a></li>
                    <li><a href="<?php echo site_url('/curso/sugestao/listar?f=rec') ?>">Recomendados</a></li>
                    <li><a href="<?php echo site_url('/curso/sugestao/listar?f=acc') ?>">Sugestões Aceitas</a></li>
                    <li><a href="<?php echo site_url('/curso/sugestao/listar?f=meu') ?>">Minhas Sugestões</a></li>
                </ul>
                <div id="form-segmentos" <?php if($areaAtual == '0') echo 'style="display: none;"' ?>>
                    <?php echo form_open() ?>
                        <dl>
                            <dt><label for="slt-area">Área de Segmento</label></dt>
                                <dd><?php echo form_dropdown('area', $listaAreas, $areaAtual, 'id="slt-area"') ?></dd>
                            <dt><label for="slt-segmento">Segmento do Curso</label></dt>
                                <dd><?php echo form_dropdown('segmento', $listaSegmentos, $segmentoAtual, 'id="slt-segmento"') ?></dd>
                        </dl>
                    <?php echo form_close() ?>
                </div>
            </nav>
        </header>
        <?php if( ! $haSugestoes ): ?>
        <div><h4>Nenhuma sugestão de curso para ser listada.</h4></div>
        <?php else: ?>
        <div id="lista-sugestoes">
            <?php echo $listaSugestoes ?>
        </div>
        <footer>
            <p id="prox-pagina">
            <?php if($haProximos): ?>
                <a href="#" data-proximo="<?php echo $primeiroProximos->id ?>">Mais sugestões</a>
            <?php else: ?>
                <span>Não há mais Sugestões a serem exibidas.</span>
            <?php endif; ?>
            </p>
        </footer>
        <?php endif ?>
    </div>
</div>