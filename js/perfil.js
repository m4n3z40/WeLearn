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
        title:'Enviar Convite',
        width: 400,
        height: 220,
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
            },
            "Cancelar": function(){
                formConvite.dialog("close");
            }

        }
    });


    $( "#enviar-convite" ).click(function(e) {
        e.preventDefault();
        formConvite.children('#txt-convite');
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
                                 'você enviou uma solicitação de amizade para '+$('#nome-usuario-perfil').val()+
                                 '</br>'+
                                 $('#msg-convite').val()+
                                '</form>');


        containerConvite.dialog(

        {
            autoOpen: false,
                modal: true,
            draggable: false,
            resizable: false,
            title: 'Convite Pendente',
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
                                    'você recebeu uma solicitação de amizade de '+$('#nome-usuario-perfil').val()+
                                    '</br>'+
                                    $('#msg-convite').val()+
                                '</form>');

        containerConvite.dialog(
            {
                autoOpen: false,
                modal: true,
                draggable: false,
                resizable: false,
                title: 'Convite Pendente',
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

    var formMensagem=$('<form action="http://welearn.com/usuario/perfil/enviar_mensagem" method="post" accept-charset="utf-8" id="form-criar-mensagem" title="Digite sua mensagem" style="display:none">'+
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
        height: 220,
        buttons: {
            "Confirmar": function() {

                WeLearn.validarForm(formMensagem,
                    $(formMensagem).attr('action'),
                    function(res)
                    {
                        WeLearn.notificar(res.notificacao);
                        formMensagem.dialog("close");
                    }
                )

            },
            "Cancelar": function(){
                formMensagem.dialog('close');
            }
        }
    });

    $( "#enviar-mensagem" ).click(function(e) {
        e.preventDefault();
        formMensagem.children('#txt-mensagem').val('');
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
                title:'Remover Amizade',
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

})();