
<?=form_open($formAction , array('id' => 'form-criar-feed'))?>
    <legend>selecione o tipo de feed</legend>
    <div>
        <?= form_radio('tipo-feed',WeLearn_Compartilhamento_TipoFeed::STATUS,true,'id = "feed-status"')?><label for="feed-status">Status</label>
        <?= form_radio('tipo-feed',WeLearn_Compartilhamento_TipoFeed::LINK,false,'id = "feed-link"')?><label for="feed-link">Link</label>
        <?= form_radio('tipo-feed',WeLearn_Compartilhamento_TipoFeed::IMAGEM,false,'id = "feed-imagem"')?><label for="feed-imagem">Imagem</label>
        <?= form_radio('tipo-feed',WeLearn_Compartilhamento_TipoFeed::VIDEO,false,'id = "feed-video"')?><label for="feed-video">Video</label>
    </div>
    <?=form_textarea( array('name' => 'descricao-feed', 'id' => 'descricao-feed', 'rows' => '1' , 'cols' => '50','style' => 'display:none'))?>
    <?=form_textarea( array('name' => 'conteudo-feed' , 'id' => 'conteudo-feed', 'rows' => '1' ,'cols' =>'50'))?>
    <?=form_submit(array('name' => 'feed-submit' , 'id' => 'feed-submit' , 'value' => 'Enviar'))?>
<?=form_close();?>