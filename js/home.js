$(document).ready(function() {

    $('#feed-submit').click(
        function(e){
           e.preventDefault();
            if($('#feed-video').is(':checked'))// verifica se o feed é um video, caso sim, verifica se a url é valida
            {
                var form= document.getElementById('form-criar-feed');
                var url = 'feed/validar_url';
                WeLearn.validarForm(form,url,function(res){
                    if(res.success){
                        var form= document.getElementById('form-criar-feed');
                        var url=$(form).attr('action');
                        WeLearn.validarForm(form,url,function(res){
                            if(res.success){
                                location.reload()
                            }
                        });
                    }else
                    {
                        location.reload()
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



    $("#paginacao-feed").click(
        function(e){
            e.preventDefault();
            var url= $(this).attr('href');
            var proximaPagina= $('#id-prox-pagina').val();
            url+='/'+proximaPagina;
            alert(url);
            $.get(
                WeLearn.url.siteURL(url),
                (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
                function(res) {
                    if (res.success) {
                        $('#feed-lista-feeds').append(res.htmlListaFeeds);

                        if(res.paginacao.proxima_pagina) {
                            $('#id-prox-pagina').val(res.paginacao.inicio_proxima_pagina);
                        } else {
                            $('#paginacao-feed').parent().html('<h4>Não existem novos feeds.</h4>');
                            $('#paginacao-feed').remove();
                        }
                        //alert(res.paginacao.proxima_pagina);


                    }else {
                        WeLearn.notificar({
                            msg: res.errors[0].error_msg,
                            nivel: 'error',
                            tempo: 10000
                        });
                    }
                },'json'
            )
        }
    );


} );