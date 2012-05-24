/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 25/04/12
 * Time: 17:55
 * To change this template use File | Settings | File Templates.
 */
(function(){


    var idConvite;
    var idRemetente;
    var idDestinatario;
    var convite;
    var tipoView;
    var url;

    $('#remover-convite').dialog(
        {
            autoOpen: false,
            modal: true,
            draggable: false,
            resizable: false,
            title:'Remover Convite',
            width: 400,
            height: 170,
            buttons: {
                "Confirmar": function() {
                        $.post(
                            WeLearn.url.siteURL(url),
                            function(result) {
                                convite.remove();
                                WeLearn.notificar(result.notificacao);
                            },
                            'json'
                        );
                        $(this).dialog("close");
                },
                "Cancelar": function(){
                    $(this).dialog("close");
                }
            }
        }
    );



    $('#confirmar-amizade').dialog(
        {
            autoOpen: false,
            modal:true,
            draggable: false,
            resizable : false,
            title:'Aceitar Convite',
            width: 400,
            height: 170,
            buttons: {
                "Confirmar": function() {
                    $.post(
                        WeLearn.url.siteURL(url),
                        function(result){
                            convite.remove();
                            WeLearn.notificar(result.notificacao);
                        },'json'
                    );
                    $(this).dialog("close");
                },
                "Cancelar": function(){
                    $(this).dialog("close");
                }
            }
        }
    );



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
                        $('#lista-convites').append(res.htmlListaConvites);
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
            e.preventDefault();
             idConvite = $(this).parent().children('.id-convite').val();
             idRemetente =$(this).parent().children('.id-remetente').val();
             idDestinatario=$(this).parent().children('.id-destinatario').val();
             convite=$('#'+idConvite);
             tipoView='lista';
             url = $(this).attr('href');
             url+='/'+idConvite+'/'+idRemetente+'/'+idDestinatario+'/'+tipoView;
            $('#remover-convite').dialog( "open");
        }
    );

    $('.aceitar-convite').live('click',function(e){
        e.preventDefault();
        idConvite = $(this).parent().children('.id-convite').val();
        idRemetente =$(this).parent().children('.id-remetente').val();
        idDestinatario=$(this).parent().children('.id-destinatario').val();
        convite=$('#'+idConvite);
        tipoView='lista';
        url = $(this).attr('href');
        url+='/'+idConvite+'/'+idRemetente+'/'+idDestinatario+'/'+tipoView;
        $('#confirmar-amizade').dialog("open");
    });


})();