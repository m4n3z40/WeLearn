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
            <nav>
                <ul>
                    <li><a href="">Mais Recentes</a></li>
                    <li><a href="">Populares</a></li>
                    <li>
                        <a href="">Por área ou segmento</a>
                        <?php echo form_open() ?>
                        <?php echo form_close() ?>
                    </li>
                    <li><a href="">Recomendados</a></li>
                    <li><a href="">Sugestões Aceitas</a></li>
                </ul>
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
        <?php endif ?>
        </div>
        <footer>
            <p id="prox-pagina">
            <?php if($haProximos): ?>
                <a href="#" data-proximo="<?php echo $primeiroProximos->id ?>">Mais sugestões</a>
            <?php else: ?>
                Não há mais Sugestões a serem exibidas.
            <?php endif; ?>
            </p>
        </footer>
    </div>
</div>