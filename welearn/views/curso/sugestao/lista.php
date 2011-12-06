<div>
    <header>
        <hgroup>
            <h1>Sugestões de Cursos</h1>
            <h3>Cursos que possívelmente não existem no serviço e interessam aos usuários</h3>
        </hgroup>
        <div>
            <h4>
                Não achou o curso que procura?
                <?php echo anchor('/curso/sugestao/criar', 'Sugira um Curso.') ?>
            </h4>
            <p>
                Divulgue a sugestão e aguarde, logo logo sua sugestão se tornará um curso!<br/>
                Não precisa visitar a sugestão sempre, você será notificado assim que um curso for criado a partir
                da sua sugestão ou de uma sugestão que você votou.
            </p>
        </div>
    </header>
    <div>
        <header>
            <h4>Filtros</h4>
            <nav id="tab-filtro">
                <ul>
                    <li><?php echo anchor('/curso/sugestao/listar?f=new', 'Mais Recentes') ?></li>
                    <li><?php echo anchor('/curso/sugestao/listar?f=pop', 'Populares') ?></li>
                    <li><?php echo anchor('/curso/sugestao/listar?f=rec', 'Recomendados') ?></li>
                    <li><?php echo anchor('/curso/sugestao/listar?f=acc', 'Sugestões Aceitas') ?></li>
                    <li><?php echo anchor('/curso/sugestao/listar?f=meu', 'Minhas Sugestões') ?></li>
                </ul>

                <?php if( $filtravelPorAreaOuSegmento ): ?>
                <p><a href="#form-segmentos">Filtrar resultados por área ou segmento</a></p>
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
                <?php endif ?>

                <?php if( $minhasSugestoesEmEspera ): ?>
                    <p><?php echo anchor('/curso/sugestao/listar?f=meu&st=acc','Suas sugestões que geraram cursos') ?></p>
                <?php elseif( $minhasSugestoesAceitas ): ?>
                    <p><?php echo anchor('/curso/sugestao/listar?f=meu','Suas sugestões em espera') ?></p>
                <?php endif; ?>
            </nav>
        </header>
        <h3><?php echo $tituloLista ?></h3>
        <?php if( ! $haSugestoes ): ?>
        <div><h4>Nenhuma sugestão de curso para ser listada.</h4></div>
        <?php else: ?>
        <div id="lista-sugestoes">
            <?php echo $listaSugestoes ?>
        </div>
        <footer>
            <p id="prox-pagina">
            <?php if($haProximos): ?>
                <a href="#" data-proximo="<?php echo $primeiroProximos ?>">Mais sugestões</a>
            <?php else: ?>
                <span>Não há mais Sugestões a serem exibidas.</span>
            <?php endif; ?>
            </p>
        </footer>
        <?php endif ?>
    </div>
</div>