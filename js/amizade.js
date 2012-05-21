/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 01/05/12
 * Time: 23:44
 * To change this template use File | Settings | File Templates.
 */
(function(){

$('#paginacaoAmigo').click(
    function(e){
        e.preventDefault();
        var url=$(this).attr('href');
        var proxAmigo=$('#id-prox-pagina').val();
        url+='/'+proxAmigo;
        $.get(
            WeLearn.url.siteURL(url),
            (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
            function(res) {
                if (res.success) {
                    $('#ul-amigos-listar-lista').append(res.htmlListaAmigos);

                    if(res.paginacao.proxima_pagina) {
                        $('#id-prox-pagina').val(res.paginacao.inicio_proxima_pagina);
                    } else {
                        $('#paginacaoAmigo').parent().html('<h4>Não há mais mensagens à serem exibida.</h4>');
                        $('#paginacaoAmigo').remove();
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



$('#a-paginacao-busca-usuarios').click(
    function(e)
    {
       e.preventDefault();
       var url=$(this).attr('href');
       var proximo=$(this).data( 'proximo');
       url+='/'+proximo;
        $.get(
            WeLearn.url.siteURL(url),
            (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
            function(res) {
                if (res.success) {
                    $('#ul-lista-resultados-busca-usuarios').append(res.htmlResultadosBusca);

                    if(res.paginacao.proxima_pagina) {
                        $('#a-paginacao-busca-usuarios').data( 'proximo', res.paginacao.inicio_proxima_pagina );

                    } else {
                        $('#a-paginacao-busca-usuarios').replaceWith('<h4>Não há mais resultados há serem exibidos.</h4>');

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