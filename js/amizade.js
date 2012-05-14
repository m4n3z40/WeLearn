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

})();