/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 25/04/12
 * Time: 17:55
 * To change this template use File | Settings | File Templates.
 */
(function(){



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
            var url = $(this).attr('href');
            url+='/'+idConvite;
            var convite=$('#'+idConvite);
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

    $('#exibir-convite-pendente').click(
        function(e)
        {
            e.preventDefault();
            $( "#container-convite-pendente" ).dialog( "open");
            return false;
        }
    );


    $('#container-convite-pendente').dialog(
        {
            autoOpen: false,
            show: "blind",
            width: 400,
            height: 170
        }
    );

})();