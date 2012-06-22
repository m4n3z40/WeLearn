<?if($listaUsuarios):?>
<div>
    <h3>Usuarios encontrados</h3>
    <ul id="listaUsuarios" class="ul-grid-cursos-alunos">
        <?php
            foreach ($listaUsuarios as $row){
              echo '<li>'.$row->toHTML('imagem_pequena').'</li>';
            }
        ?>
    </ul>
    <div>
    <?php
        if($paginacaoUsuario['proxima_pagina']){
            echo anchor( '/usuario/amigos/buscar?busca='. $texto,'Mais resultados...');
        }
    ?>
    </div>
</div>
<hr>
<?endif;?>

<?if($listaCursos):?>
<div>
    <h3>Cursos encontrados</h3>
    <ul id = "listaCursos" class="ul-grid-cursos-alunos">
        <?php
            foreach($listaCursos as $row){
                echo '<li>'.$row->htmlImagemLink(true).'</li>';
            }
        ?>
    </ul>
    <div>
    <?php
        if($paginacaoCurso['proxima_pagina']){
            echo anchor('/curso/buscar?busca='.$texto.'&tipo-busca=tudo&area=0&segmento=0','Mais resultados...');
        }
    ?>
    </div>
</div>
<?endif;?>

