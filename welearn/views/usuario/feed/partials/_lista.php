
<?for ($i = 0; $i < $qtdFeeds; $i++):?>
    <li id="item-feed-<?echo $feeds_usuario[$i]->id?>">
        <input type='hidden' id='id-feed' value='<?echo $feeds_usuario[$i]->id?>'/>
        <?echo $feeds_usuario[$i]->criador->toHTML('imagem_pequena')?>
        <?php
            switch ($feeds_usuario[$i]->tipo) {
                case WeLearn_Compartilhamento_TipoFeed::IMAGEM:
                    echo 'Compartilhou uma Imagem:';
                    echo '<div>'.$feeds_usuario[$i]->descricao.'</div>';
                    echo '<img src="'.$feeds_usuario[$i]->conteudo.'" alt="'.$feeds_usuario[$i]->conteudo.'" height="250" width="250" />';
                    break;
                case WeLearn_Compartilhamento_TipoFeed::LINK:
                    echo "Compartilhou um Link:";
                    echo '<a href="'.prep_url($feeds_usuario[$i]->conteudo).'"TARGET="_blank">'.$feeds_usuario[$i]->descricao.'</a>';
                    break;
                case WeLearn_Compartilhamento_TipoFeed::VIDEO:
                    echo "Compartilhou um Video:";
                    echo '<div>'.$feeds_usuario[$i]->descricao.'</div>';
                    echo html_entity_decode($feeds_usuario[$i]->conteudo);
                    break;
                case WeLearn_Compartilhamento_TipoFeed::STATUS:
                    echo 'Compartilhou um novo Status:';
                    echo '<div>'.$feeds_usuario[$i]->conteudo.'</div>';
                    break;
            }
        ?>
        <div id="feed-data">
            <div>Criado em:</div>
            <span><?echo date('d/m/Y à\s H:i',$feeds_usuario[$i]->dataEnvio)?></span>
        </div>

        <?php
            if(isset($usuarioPerfil))
            {
                if($usuarioAutenticado == $usuarioPerfil || $usuarioAutenticado->id == $feeds_usuario[$i]->criador->id){
                    echo anchor('feed/remover_timeline/'.$feeds_usuario[$i]->id.'/'.$usuarioPerfil->id,'remover',array('id' => 'remover'));
                }
            }else{
                if($usuarioAutenticado->id == $feeds_usuario[$i]->criador->id){
                    echo anchor('feed/remover_feed/'.$feeds_usuario[$i]->id,'remover',array('id' => 'remover'));
                }
            }
        ?>
        <?echo anchor('#','comentar',array('id' => 'exibir-barra-de-comentario'))?>
        <?if(count($comentarios_feed[$i])>0):?>
            <?php if ($comentarios_feed[$i]['haMaisPaginas']): ?>
                <a href="comentario_feed/proxima_pagina/"  id="paginacao-comentario" data-proximo="<?echo $comentarios_feed[$i]['paginacao']['inicio_proxima_pagina']?>" data-id-feed="<?php echo $feeds_usuario[$i]->id ?>" >Comentarios mais Antigos</a>
            <?php else: ?>
                <h4>Não existem novos comentarios para este compartilhamento.</h4>
            <?php endif; ?>
            <ul>
                <?echo $comentarios_feed[$i]['HTMLcomentarios'] ?>
            </ul>
        <?endif;?>
    </li>
<?endfor;?>

<?if(isset($criarComentario)):?>
    <?echo $criarComentario;?>
<?endif;?>
