
<?foreach ( $feeds_usuario as $row):?>
    <li>
        <input type='hidden' id='id-feed' value='<?=$row->id?>'/>
        <?=$row->criador->toHTML('imagem_pequena')?>
        <?php
            switch ($row->tipo) {
                case WeLearn_Compartilhamento_TipoFeed::IMAGEM:
                    echo 'compartilhou uma imagem:';
                    echo '<div>'.$row->descricao.'</div>';
                    echo '<img src="'.$row->conteudo.'" alt="'.$row->conteudo.'" height="250" width="250" />';
                    break;
                case WeLearn_Compartilhamento_TipoFeed::LINK:
                    echo "compartilhou um link:";
                    echo '<div>'.$row->descricao.'</div>';
                    echo '<a href="'.prep_url($row->conteudo).'"TARGET="_blank">'.$row->conteudo.'</a>';
                    break;
                case WeLearn_Compartilhamento_TipoFeed::VIDEO:
                    echo "compartilhou um video:";
                    echo '<div>'.$row->descricao.'</div>';
                    echo html_entity_decode($row->conteudo);
                    break;
                case WeLearn_Compartilhamento_TipoFeed::STATUS:
                    echo 'compartilhou um novo status:';
                    echo '<div>'.$row->conteudo.'</div>';
                    break;
            }
        ?>
        <div id="feed-data"><?=date('d/m/Y à\s H:i',$row->dataEnvio)?></div>
        <?if(count($comentarios_feed[$row->id])>0):?>
           <ul id='ul-comentario-listar-lista'>
               <?foreach($comentarios_feed[$row->id] as $comentario):?>
                    <?=$comentario->criador->toHTML('imagem_mini');?>
                    <?=$comentario->id;?>
               <?endforeach;?>
           </ul>
        <?endif;?>
        <?php
            if($usuarioAutenticado->id == $row->criador->id){
                echo anchor('feed/remover_feed/'.$row->id,'remover',array('id' => 'remover-feed'));
              }
        ?>
        <?=anchor('#','comentar',array('id' => 'exibir-barra-de-comentario'))?>
        <hr>
    </li>
<?endforeach;?>

<?if(isset($criarComentario)):?>
    <?=$criarComentario;?>
<?endif;?>
