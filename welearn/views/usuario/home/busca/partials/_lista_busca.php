<?if($listaUsuarios):?>
    <h3>Resultado da busca para usuarios</h3>
    <div id='listaUsuarios'>
        <?php
            foreach ($listaUsuarios as $row){
              echo '<li>'.$row->toHTML('imagem_pequena').'</li>';
            }
            if($paginacaoUsuario['proxima_pagina']){
                echo anchor(site_url('/usuario/amigos/buscar?busca='.$texto),'mais resultados');
            }
        ?>
    </div>
<?endif;?>

<?if($listaCursos):?>
    <h3>Resultado da busca para cursos</h3>
    <div id = 'listaCursos'>
        <?php
            foreach($listaCursos as $row){
                echo $row->htmlImagemLink(true);
            }
            if($paginacaoCurso['proxima_pagina']){
                echo anchor(site_url('/curso/buscar?busca='.$texto.'&tipo-busca=tudo&area=0&segmento=0'),'mais resultados');
            }
        ?>
    </div>
<?endif;?>

