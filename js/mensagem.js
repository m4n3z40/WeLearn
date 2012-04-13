(function() {

    $('#paginacaoMensagem').click(function(e) {

        e.preventDefault();

        var idAmigo=$('#id-amigo-mensagens').val();
        var proxMensagem=$('#id-prox-pagina').val();
        var url = WeLearn.url.siteURL('usuario/mensagem/proxima_pagina/' + idAmigo + '/' + proxMensagem);

        $.get(
            url,
            (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
            function(res) {
                if (res.success) {
                    $('#mensagem-lista-mensagens').prepend(res.htmlListaMensagens);

                    if(res.paginacao.proxima_pagina) {
                        $('#id-prox-pagina').val(res.paginacao.inicio_proxima_pagina);
                    } else {
                        $('#paginacaoMensagem').parent().html('<h4>Não há mais mensagens a serem exibida.</h4>');
                        $('#paginacaoMensagem').remove();
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
    });

    var formPost = document.getElementById('form-criar-mensagem');
    $('#btn-form-mensagem').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formPost,
            $(formPost).attr('action'),
            function(res){
                log(res);
            }
        );
    });

    $('.remover-mensagem').live('click', function(e) {
        e.preventDefault();

        var url=$(this).attr('href');
        var mensagem=$(this).parent();// elemento item-lista-mensagem a ser removido
        var idMensagem=$(this).parent().children('#id-mensagem').val();
        var idAmigo=$('#id-amigo-mensagens').val();
        url+='/'+idMensagem;
        url+='/'+idAmigo;
        $.post(
            WeLearn.url.siteURL(url),
            function(result) {
                if (result.success) {
                   mensagem.remove();
                } else {
                    alert('erro ao executar operação');
                }
            },
            'json'
        );

    });

})();