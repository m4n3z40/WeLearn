(function(){

    var $emQtdRequisicoes = $('.em-qtd-requisicoes'),
        $emTotalRequisicoes = $('.em-total-requicoes'),
        $aMaisRequisicoes = $('#a-paginacao-requisicoes'),
        $ulListaRequisicoes = $('#ul-lista-requisicoes');

    $aMaisRequisicoes.click(function(e){
        e.preventDefault();

        var $this = $(this);

        $.get(
            WeLearn.url.siteURL('/curso/aluno/mais_requisicoes/' + $this.data('id-curso')),
            { 'proximo': $this.data('proximo') },
            function(res) {
                if (res.success) {

                    $ulListaRequisicoes.append( res.htmlListaRequisicoes );
                    $emQtdRequisicoes.text( parseInt( $emQtdRequisicoes.text() ) + res.qtdRequisicoes );

                    if (res.paginacao.proxima_pagina) {
                        $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                    } else {
                        $this.replaceWith('<h4>Não há mais requisições a serem exibidas.</h4>');
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

    $('a.a-aceitar-requisicao-inscricao, a.a-recusar-requisicao-inscricao').live('click', function(e){
        e.preventDefault();

        var $this = $(this);

        $.get(
            $this.attr('href'),
            { 'id-usuario': $this.data('id-usuario') },
            function(res) {
                if ( res.success ) {

                    $this.parent().parent().parent().parent().fadeOut(function(){
                        $(this).remove();
                    });

                    WeLearn.notificar(res.notificacao);

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