/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 25/04/12
 * Time: 17:55
 * To change this template use File | Settings | File Templates.
 */
(function(){

//////////////////////////////////////////////acoes de convite perfil////////////////////////////

    $( "#convite-form" ).dialog({
        autoOpen: false,
        show: "blind",
        width: 400,
        height: 170
    });

    $( "#enviar-convite" ).click(function(e) {
        e.preventDefault();
        $( "#convite-form" ).dialog( "open");
        return false;
    });

    var formpost=document.getElementById('form-enviar-convite');
    $('#btn-form-convite').click(function(e){
        e.preventDefault();
        WeLearn.validarForm(formpost,
            $(formpost).attr('action'),
            function(res)
            {
                if(res.success)
                {
                    $('#convite-form').dialog("close");
                    location.reload();
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
                show: "blind",
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
                show: "blind",
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
///////////////////////////////////////////acoes de convite perfil////////////////////////////////////////////////





   ///////////////////////acoes da pagina listar convites///////////////////////////////



    $('#paginacao-convite').click(
        function(e){
            e.preventDefault();
            var idPaginacao= $(this).attr('data-proximo');
            var param=$('#tipo-convite').val();
            var url = WeLearn.url.siteURL($('#paginacao-convite').attr('href') + param + '/' + idPaginacao);

            $.get(url
                ,
                (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
                function(res) {
                    if (res.success) {
                        $('#lista-convites').prepend(res.htmlListaConvites);
                        if(res.paginacao.proxima_pagina) {
                            $('#id-prox-pagina').val(res.paginacao.inicio_proxima_pagina);
                        } else {
                            $('#paginacao-convite').parent().html('<h4>Não há mais convites à serem exibidos.</h4>');
                            $('#paginacao-convite').remove();
                        }
                    }else{

                    }
                },'json'
            )

        }
    );

    $('.remover-convite').live('click',
        function(e)
        {
            var idConvite = $(this).parent().children('.id-convite').val();
            var idRemetente =$(this).parent().children('.id-remetente').val();
            var idDestinatario=$(this).parent().children('.id-destinatario').val();
            var convite=$('#'+idConvite);
            var tipoView='lista';
            var url = $(this).attr('href');
            url+='/'+idConvite+'/'+idRemetente+'/'+idDestinatario+'/'+tipoView;
            e.preventDefault();
            $.post(
                WeLearn.url.siteURL(url),
                function(result) {
                    if (result.success) {
                        convite.remove();
                        WeLearn.notificar(result.notificacao);
                    } else {
                        WeLearn.notificar(result.notificacao);
                    }
                },
                'json'
            );
        }
    );

    $('.aceitar-convite').live('click',function(e){
        e.preventDefault();
        var idConvite = $(this).parent().children('.id-convite').val();
        var idRemetente =$(this).parent().children('.id-remetente').val();
        var idDestinatario=$(this).parent().children('.id-destinatario').val();
        var convite=$('#'+idConvite);
        var tipoView='lista';
        var url = $(this).attr('href');
        url+='/'+idConvite+'/'+idRemetente+'/'+idDestinatario+'/'+tipoView;
        var convite=$('#'+idConvite);
        $.post(
            WeLearn.url.siteURL(url),
            function(result){
                if(result.success){
                    convite.remove();
                    WeLearn.notificar(result.notificacao);
                } else {
                    WeLearn.notificar(result.notificacao);
                }
            },'json'
        );
    });
    ///////////////////////acoes da pagina listar convites///////////////////////////////



})();