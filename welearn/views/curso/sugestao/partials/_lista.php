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
            <td><?php echo date('d/m/Y à\s H:i', $sugestao->dataCriacao) ?></td>
        </tr>
        <tr>
            <td>Criador da Sugestão</td>
            <td><?php echo anchor('usuario/perfil/' . $sugestao->criador->id, $sugestao->criador->nome) ?></td>
        </tr>
    </table>
    <footer>
        <div>
            <h4>Popularidade</h4>
            <ul>
                <li class="qtd-votos"><span><?php echo $sugestao->votos ?></span> Votos</li>
                <li class="votar-sugestao"><a href="#" data-id-sugestao="<?php echo $sugestao->id ?>">Votar</a></li>
            </ul>
        </div>
        <h4>
            <a href="#" id="criar-curso" data-id-sugestao="<?php echo $sugestao->id ?>">
            Criar curso à partir desta sugestão</a>
        </h4>
    </footer>
</div>
<?php endforeach ?>