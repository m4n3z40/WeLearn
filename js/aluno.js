(function(){

    var $emQtdRequisicoes = $('.em-qtd-requisicoes'),
        $emTotalRequisicoes = $('.em-total-requisicoes'),
        $aMaisRequisicoes = $('#a-paginacao-requisicoes'),
        $ulListaRequisicoes = $('#ul-lista-requisicoes'),
        $emQtdAlunos = $('#em-qtd-alunos'),
        $emTotalAlunos = $('#em-total-alunos'),
        $ulListaAlunos = $('#ul-lista-alunos'),
        $aMaisAlunos = $('#a-paginacao-alunos');

    $aMaisAlunos.click(function(e){
        e.preventDefault();

        var $this = $(this);

        $.get(
            WeLearn.url.siteURL('/curso/aluno/mais_alunos/' + $this.data('id-curso')),
            { 'proximo': $this.data('proximo') },
            function(res) {
                if (res.success) {

                    $ulListaAlunos.append( res.htmlListaAlunos );
                    $emQtdAlunos.text( parseInt( $emQtdAlunos.text() ) + res.qtdAlunos );

                    if (res.paginacao.proxima_pagina) {
                        $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                    } else {
                        $this.replaceWith('<h4>Não há mais alunos a serem exibidos.</h4>');
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

    var $divConfirmacaoDesvincular = $('<div id="dialogo-confirmacao-desvincular-aluno">' +
                                       '<p>Tem certeza que deseja desvincular este aluno?<br/>' +
                                       'Talvez você queira avisá-lo antes de fazer isso :)</div>');
    $('.a-desvincular-aluno').live('click', function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoDesvincular.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        $this.attr('href'),
                        { 'id-aluno': $this.data('id-aluno') },
                        function(res) {
                            if ( res.success ) {

                                $this.parent().parent().parent().parent().fadeOut(function(){
                                    $(this).remove();

                                    $emQtdAlunos.text( parseInt( $emQtdAlunos.text() ) - 1 );
                                    $emTotalAlunos.text( parseInt( $emTotalAlunos.text() ) - 1 );
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

                    $( this ).dialog('close');
                },
                'Cancelar' : function() {
                    $( this ).dialog('close');
                }
            }
        });
    });

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

                        $emQtdRequisicoes.text( parseInt( $emQtdRequisicoes.text() ) - 1 );
                        $emTotalRequisicoes.text( parseInt( $emTotalRequisicoes.text() ) - 1 );
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