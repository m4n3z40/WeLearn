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
                </ul>
                <div id="form-segmentos" style="display: none;">
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
        <div id="lista-sugestoes">
        <?php if(empty($sugestoes)): ?>
            <p>Nenhuma sugestão de curso para ser listada.</p>
        <?php else: ?>
            <?php foreach ($sugestoes as $sugestao): ?>
            <h3><a href="#"><?php echo $sugestao->nome ?></a></h3>
            <div>
                <table>
                    <tr>
                        <td>Nome</td>
                        <td><?php echo $sugestao->nome ?></td>
                    </tr>
                    <tr>
                        <td>Tema</td>
                        <td><?php echo $sugestao->tema ?></td>
                    </tr>
                    <tr>
                        <td>Descrição</td>
                        <td><?php echo $sugestao->descricao ?></td>
                    </tr>
                    <tr>
                        <td>Área de segmento</td>
                        <td><?php echo $sugestao->segmento->area->descricao ?></td>
                    </tr>
                    <tr>
                        <td>Segmento</td>
                        <td><?php echo $sugestao->segmento->descricao ?></td>
                    </tr>
                    <tr>
                        <td>Data de Criação</td>
                        <td><?php echo date('d/m/Y à\s H:m', $sugestao->dataCriacao) ?></td>
                    </tr>
                    <tr>
                        <td>Criador da Sugestão</td>
                        <td><?php echo anchor('usuario/perfil/' . $sugestao->criador->id, $sugestao->criador->nome) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="#">Criar curso à partir desta sugestão</a>
                        </td>
                    </tr>
                </table>
            </div>
            <?php endforeach ?>
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