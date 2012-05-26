/**
 * Created by JetBrains PhpStorm.
 * User: Thiago
 * Date: 24/05/12
 * Time: 18:30
 * To change this template use File | Settings | File Templates.
 */
(function(){
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



$('#feed-submit').click(function(e){
    e.preventDefault();
    var form = document.getElementById('form-criar-feed'),
    url = $(form).attr('action');
    WeLearn.validarForm(form,url,function(res){

            location.reload()

    });
});


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




})();