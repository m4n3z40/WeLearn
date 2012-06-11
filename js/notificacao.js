(function(){

    var $ulListaNotificacoes = $('#ul-lista-notificacoes');

    $('#a-paginacao-notificacao').click(function(e){
        e.preventDefault();

        var $this = $(this),
            url = WeLearn.url.siteURL('/notificacao/proxima_pagina/' + $this.data('proximo'));

        $.get(
            url,
            {},
            function(res) {
                if (res.success) {

                    $ulListaNotificacoes.append(res.htmlListaNotificacoes);

                    if ( res.paginacao.proxima_pagina ) {
                        $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                    } else {
                        $this.remove();
                    }

                } else {
                    WeLearn.notificar({
                        nivel: 'error',
                        msg: res.errors[0].error_msg,
                        tempo: 5000
                    });
                }
            }
        );
    });

})();