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
        <?php
            if($usuarioAutenticado == $usuarioPerfil || $row->criador->id == $usuarioAutenticado->id){
                echo anchor('feed/remover_timeline/'.$row->id.'/'.$usuarioPerfil->id,'remover',array('id' => 'remover-timeline'));
            }
        ?>

        <hr>
    </li>

    <?endforeach;?>
</div>