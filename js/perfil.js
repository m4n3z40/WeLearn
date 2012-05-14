/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 11/05/12
 * Time: 19:55
 * To change this template use File | Settings | File Templates.
 */


(function(){


    $( "#convite-form" ).dialog({
        autoOpen: false,
        width: 400,
        height: 170
    });



    // exibir formulario de convite
    $( "#enviar-convite" ).click(function(e) {
        e.preventDefault();
        $( "#convite-form" ).dialog( "open");
        return false;
    });

    // enviar convite
    var formConvite=document.getElementById('form-enviar-convite');
    $('#btn-form-convite').click(function(e){
        e.preventDefault();
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

            }
        )});

    $('#exibir-convite-pendente').click(
        function(e)
        {
            e.preventDefault();
            $( "#container-convite-pendente" ).dialog( "open");
            return false;
        }
    );





    var param = $('.param-tipo-convite').val();
    var idConvite = $('#id-convite').val();
    var idRemetente=$('#id-remetente').val();
    var idDestinatario=$('#id-destinatario').val();



    if(param=='enviado'){
        $('#container-convite-pendente').dialog(
            {
                autoOpen: false,
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


    }else{
        $('#container-convite-pendente').dialog(
            {
                autoOpen: false,
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




    var formMensagem=document.getElementById('form-criar-mensagem');
    $( "#form-criar-mensagem" ).dialog({
        autoOpen: false,
        modal:true,
        title:'Enviar Mensagem',
        width: 400,
        height: 170,
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
        $( "#form-criar-mensagem" ).dialog( "open");
    });






})();