<div>
    <?foreach ( $feeds_usuario as $row):?>
        <li>
        <?=$row->getCriador()->toHTML('imagem_pequena')?>
        <?php
        switch ($row->getTipo()) {
            case WeLearn_Compartilhamento_TipoFeed::IMAGEM:
                echo 'compartilhou uma imagem:';
                echo '<div>'.$row->descricao.'</div>';
                echo '<img src="'.$row->conteudo.'" alt="'.$row->conteudo.'" height="250" width="250" />';
                break;
            case WeLearn_Compartilhamento_TipoFeed::LINK:
                echo "compartilhou um link:";
                echo '<div>'.$row->descricao.'</div>';
                echo '<a href="http://'.$row->conteudo.'"TARGET="_blank">'.$row->conteudo.'</a>';
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
            <?php
                if(isset($usuarioPerfil)){
                   if($usuarioAutenticado == $usuarioPerfil){
                       echo anchor('feed/removerTimeLine/'.$row->id.'/'.$usuarioPerfil->id,'remover',array('id' => 'remover-timeline'));
                   }else{
                       if($row->criador->id == $usuarioAutenticado->id){
                           echo anchor('feed/removerTimeLine/'.$row->id.'/'.$usuarioPerfil->id,'remover',array('id' => 'remover-timeline'));
                       }
                   }

                }else{
                    echo anchor('feed/remover/'.$row->id.'/'.$usuarioAutenticado->id,'removerhome',array('id' => 'remover-feed'));
                }
            ?>

        </li>
        <hr>
    <?endforeach;?>
</div>