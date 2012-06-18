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
                        $('#paginacaoMensagem').parent().html('<h4>Não há mais mensagens à serem exibida.</h4>');
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
                $('#mensagem-lista-mensagens').append(
                    '<li class="item-lista-mensagem">'+
                        '<input type="hidden" id="id-mensagem" value="'
                        +res.mensagemId+'">'+
                        '<div class="remetente">'+res.remetente+'</div>'+
                        '<div class="mensagem-texto">'+res.mensagemTexto+'</div>'+
                        '<div class="data-envio">'+res.dataEnvio+'</div>'+
                        '<a href="usuario/mensagem/remover" class="remover-mensagem">'
                        +'remover</a>'+
                        '</li>'+
                        '</div>'
                );
                $('#txt-mensagem').val('');
                WeLearn.notificar(res.notificacao);

            },
            function(res){
                $(formPost).remove();
            }
        );

    });

    $('.remover-mensagem').live('click', function(e) {
        e.preventDefault();
        var url=$(this).attr('href');
        var mensagem=$(this).parent();// elemento item-lista-mensagem a ser removido
        var idMensagem=$(this).parent().children('#id-mensagem').val();
        var idAmigo=$('#id-amigo-mensagens').val();
        url+='/'+idMensagem+'/'+idAmigo;
        $.post(
            WeLearn.url.siteURL(url),
            function(result) {
                mensagem.remove();
                WeLearn.notificar(result.notificacao);
            },
            'json'
        );

    });

})();