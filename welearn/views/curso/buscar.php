<div id="curso-buscar-content">
    <header>
        <hgroup>
            <h1>Busca de Cursos do WeLearn</h1>
            <h3>Está procurando um Curso? Está no lugar certo!</h3>
        </hgroup>
        <p></p>
    </header>
    <div>
        <header>
            <form method="get" action="<?php echo site_url($formAction) ?>" id="frm-buscar-cursos">
                <fieldset>
                    <legend>O que você está procurando?</legend>
                    <dl>
                        <dt><input type="text" name="busca" id="txt-busca" placeholder="Descreva aqui." value="<?php echo $txtBusca ?>"></dt>
                        <dd>
                            <ul>
                                <li><input type="radio"
                                           name="tipo-busca"
                                           value="recomendados"
                                           class="rdo-tipo-busca-curso"
                                           id="rdo-busca-recomendados"
                                           <?php echo ( $tipoBuscaAtual != 'recomendados' && $tipoBuscaAtual != '' ) ? '' : 'checked="checked"'?>
                                    >
                                    <label for="rdo-busca-recomendados">Buscar Cursos recomendados!</label></li>
                                <li><input type="radio"
                                           name="tipo-busca"
                                           value="tudo"
                                           class="rdo-tipo-busca-curso"
                                           id="rdo-busca-tudo"
                                           <?php echo ( $tipoBuscaAtual == 'tudo' ) ? 'checked="checked"' : '' ?>
                                    >
                                    <label for="rdo-busca-tudo">Buscar em todo WeLearn!</label></li>
                                <li><input type="radio"
                                           name="tipo-busca"
                                           value="refinada"
                                           class="rdo-tipo-busca-curso"
                                           id="rdo-busca-refinado"
                                           <?php echo ( $tipoBuscaAtual == 'refinada' ) ? 'checked="checked"' : '' ?>
                                    >
                                    <label for="rdo-busca-refinado">Buscar em Áreas específicas!</label></li>
                            </ul>
                            <ul id="ul-opcoes-busca-cursos-refinada" <?php echo ( $tipoBuscaAtual == 'refinada' ) ? '' : 'style="display:none;"' ?>>
                                <li <?php echo ( $areaAtual == '0' ) ? '' : 'style="display:none;"' ?>>
                                    <a href="#" id="a-exibir-area-busca">Escolher Área</a>
                                </li>
                                <li <?php echo ( $areaAtual == '0' ) ? 'style="display:none;"' : '' ?>>
                                    <?php echo form_dropdown('area', $dadosDropdownArea, $areaAtual, 'id="slt-area"') ?>
                                </li>
                                <li <?php echo ( $segmentoAtual == '0' ) ? '' : 'style="display:none;"' ?>>
                                    <a href="#" id="a-exibir-segmento-busca">Escolher Segmento</a>
                                </li>
                                <li <?php echo ( $segmentoAtual == '0' ) ? 'style="display:none;"' : '' ?>>
                                    <?php echo form_dropdown('segmento', $dadosDropdownSegmento, $segmentoAtual, 'id="slt-segmento"') ?>
                                </li>
                            </ul>
                        </dd>
                    </dl>
                </fieldset>
                <input type="submit" id="btn-form-busca-cursos" value="Buscar!">
            </form>
        </header>
        <div>
        <?php if ($haResultados): ?>
            <h3>Resultados para busca de <em>"<?php echo $txtBusca ?>"</em></h3>
            <ul id="ul-lista-resultados-busca-cursos" class="ul-grid-home-cursos">
                <?php echo $resultadosBusca ?>
            </ul>
            <?php if ($haMaisPaginas): ?>
                <a href="#"
                   id="a-paginacao-busca-cursos"
                   data-proximo="<?php echo $inicioProxPagina ?>">Exibir mais resultados para <em>"<?php echo $txtBusca ?>"...</em></a>
            <?php else: ?>
                <h4>Não há mais resultados a serem exibidos.</h4>
            <?php endif; ?>
        <?php else: ?>
            <h3>Nenhuma busca foi realizada ou nenhum resultado foi encontrado :(</h3>
        <?php endif; ?>
        </div>
    </div>
</div>