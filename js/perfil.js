/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 11/05/12
 * Time: 19:55
 * To change this template use File | Settings | File Templates.
 */


(function(){

    var csrf= $('input[name=welearn_csrf_token]').val();    //csrf do perfil


    var formConvite = $('<form action="'+WeLearn.url.siteURL('convite/enviar')+'" method="post" accept-charset="utf-8" id="form-enviar-convite">'+
                        '<div class="hidden">'+
                        '<input type="hidden" name="welearn_csrf_token" value="'+csrf+'" />'+
                        '</div>'+
                        '<input type="hidden" name="destinatario" value="'+$('#id-usuario-perfil').val()+'"/>'+
                        '<textarea name="txt-convite" cols="43" rows="5" ></textarea></form>');

        formConvite.dialog({
        autoOpen: false,
        modal: true,
        resizable: false,
        draggable: false,
        width: 400,
        height: 200,
        buttons:{
            "Enviar Convite": function(){

                WeLearn.validarForm(formConvite,
                    $(formConvite).attr('action'),
                    function(res)
                    {
                        if(res.success)
                        {
                            if(res.amigos)
                            {
                                $('#convite-form').dialog("close");
                                window.location = WeLearn.url.siteURL('convite/index/recebidos');
                            }else{
                                $('#convite-form').dialog("close");
                                location.reload();
                            }

                        }

                    });
            }

        }
    });


    $( "#enviar-convite" ).click(function(e) {
        e.preventDefault();
        formConvite.dialog( "open");
        return false;
    });




    var param = $('#tipo-convite').val();
    var idConvite = $('#id-convite').val();
    var idDestinatario = $('#id-destinatario').val();
    var idRemetente = $('#id-remetente').val();

    if(param == 'enviado')
    {

        var containerConvite = $('<form action="'+WeLearn.url.siteURL('convite/aceitar')+'" method="post" accept-charset="utf-8" id="form-criar-mensagem">'+
                                 'você enviou uma solicitação de amizade para '+$('#id-usuario-perfil').val()+
                                '</form>');


        containerConvite.dialog(

        {
            autoOpen: false,
                modal: true,
            draggable: false,
            resizable: false,
            width: 400,
            height: 170,
            buttons: {
            "Cancelar Requisicao": function() {
                var tipoView='perfil';
                var url='/convite/remover/'+idConvite+'/'+idRemetente+'/'+idDestinatario+'/'+tipoView;
                $.post(
                    WeLearn.url.siteURL(url),
                    function(result) {
                        location.reload();
                    },
                    'json'
                );
                $( this ).dialog( "close" );
            }
        }
        }

        );
    }

    else if(param == 'recebido')
    {
        var containerConvite = $('<form action="'+WeLearn.url.siteURL('convite/recusar')+'" method="post" accept-charset="utf-8" id="form-criar-mensagem">'+
                                    'você recebeu uma solicitação de amizade de '+$('#id-usuario-perfil').val()+
                                '</form>');

        containerConvite.dialog(
            {
                autoOpen: false,
                modal: true,
                draggable: false,
                resizable: false,
                width: 400,
                height: 170,
                buttons: {
                    "Cancelar Requisicao": function() {
                        var tipoView='perfil';
                        var url='/convite/remover/'+idConvite+'/'+idRemetente+'/'+idDestinatario+'/'+tipoView;
                        $.post(
                            WeLearn.url.siteURL(url),
                            function(result) {
                                location.reload();
                            },
                            'json'
                        );
                        $( this ).dialog( "close" );
                    },
                    "Aceitar Requisicao": function(){
                        var tipoView='perfil';
                        var url='/convite/aceitar/'+idConvite+'/'+idRemetente+'/'+idDestinatario+'/'+tipoView;
                        $.post(
                            WeLearn.url.siteURL(url),
                            function(result){
                                location.reload();
                            },'json'
                        );
                        $( this ).dialog( "close" );
                    }
                }
            }
        );
    }


    $('#exibir-convite-pendente').click(
        function(e)
        {
            e.preventDefault();

            containerConvite.dialog( "open");
            return false;

        }
    );

    var formMensagem=$('<form action="http://welearn.com/usuario/mensagem/criar" method="post" accept-charset="utf-8" id="form-criar-mensagem" title="Digite sua mensagem" style="display:none">'+
                        '<div class="hidden">'+
                        '<input type="hidden" name="welearn_csrf_token" value="'+csrf+'" />'+
                        '</div>'+
                        '<input type="hidden" name="destinatario" value="'+$('#id-usuario-perfil').val()+'" />'+
                        '<textarea rows="5" cols="43" name="mensagem" id="txt-mensagem"></textarea>'+
                        '</form>'
                        );

   formMensagem.dialog({
        autoOpen: false,
        modal: true,
        draggable: false,
        resizable: false,
        title:'Enviar Mensagem',
        width: 400,
        height: 200,
        buttons: {
            "Confirmar": function() {

                WeLearn.validarForm(formMensagem,
                    $(formMensagem).attr('action'),
                    function(res)
                    {
                        if(res.success){
                            WeLearn.notificar(res.notificacao);
                        }
                    }
                )
                $(this).dialog("close");
            },
            "Cancelar": function(){
                $(this).dialog("close");
            }
        }
    });

    $( "#enviar-mensagem" ).click(function(e) {
        e.preventDefault();
        formMensagem.dialog( "open");
    });



    var divRemoverAmizade= $('<div>Tem certeza que deseja remover a amizade?</div>');

    $('#remover-amizade').click(
        function(e){
            e.preventDefault();
            var url=$(this).attr('href');
            divRemoverAmizade.dialog({
                autoOpen: false,
                modal: true,
                draggable: false,
                resizable: false,
                title:'Enviar Mensagem',
                width: 400,
                height: 200,
                buttons: {
                    "Confirmar": function() {
                        $(this).dialog('close');
                        $.post(url,function(){
                            location.reload();
                        });
                    },
                    "Cancelar": function(){
                        $(this).dialog('close');
                    }
                }
            });
            divRemoverAmizade.dialog('open');
        }
    );


    $("input[name='tipo-feed']").change(
        function()
        {
            if($(this).val()==0)
            {
                $('#descricao-feed').fadeOut();
            }
            if($(this).val()==1)
            {
                $('#descricao-feed').attr({placeholder:'Descreva O Seu Link'});
                $('#descricao-feed').fadeIn();
            }
            if($(this).val()==2)
            {
                $('#descricao-feed').attr({placeholder:'Descreva Sua Imagem'});
                $('#descricao-feed').fadeIn();
            }
            if($(this).val()==3)
            {
                $('#descricao-feed').attr({placeholder:'Descreva Seu Video'});
                $('#descricao-feed').fadeIn();
            }
        }
    );



    $('#feed-submit').click(
        function(e){
            e.preventDefault();
            if($('#feed-video').is(':checked'))// verifica se o feed é um video, caso sim, verifica se a url é valida
            {
                var form= document.getElementById('form-criar-feed');
                var url = 'feed/validar_url';
                WeLearn.validarForm(form,url,function(res){
                    if(res.success){
                        var form = document.getElementById('form-criar-feed');
                        var url=$(form).attr('action');
                        WeLearn.validarForm(form,url,function(res){
                            if(res.success){
                                location.reload();
                            }
                        });
                    }else
                    {
                        location.reload();
                    }
                });
            }else{
                var form= document.getElementById('form-criar-feed');
                var url=$(form).attr('action');
                WeLearn.validarForm(form,url,function(res){
                    if(res.success){
                        location.reload();
                    }
                });
            }
        }
    );


})();