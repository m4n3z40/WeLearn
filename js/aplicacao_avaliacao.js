(function(){

    var $window = $(window),
        $divJanelaAvaliacao = $('<div/>', {id: 'div-janela-avaliacao'}).dialog({
            autoOpen: false,
            resizable: false,
            draggable: false,
            modal: true,
            width: 610,
            title: 'Realização de Avaliação',
            position: 'top',
            show: 'fade',
            hide: 'fade'
        }),
        exibirQuestaoAnterior = function( $liQuestoes, aQuestaoAnterior ){
            var $ulAvaliacao = $liQuestoes.parent(),
                questaoAtual = parseInt( $ulAvaliacao.data('questao-atual') ),
                $liQuestaoAtual = $liQuestoes.eq( questaoAtual - 1),
                $aQuestaoAnterior = $(aQuestaoAnterior);

            $liQuestaoAtual.hide('slide', {direction: 'right'}, function(){

                $liQuestaoAtual.prev().show('slide', {direction: 'left'});

            });

            $ulAvaliacao.data('questao-atual', --questaoAtual);

            if ( questaoAtual == 1 ) {

                $aQuestaoAnterior.parent().hide();

            }

            if ( questaoAtual < $liQuestoes.length ) {

                $aQuestaoAnterior.parent().next().show();

            }
        },
        exibirProximaQuestao = function( $liQuestoes, aProximaQuestao ){
            var $ulAvaliacao = $liQuestoes.parent(),
                questaoAtual = parseInt( $ulAvaliacao.data('questao-atual') ),
                $liQuestaoAtual = $liQuestoes.eq( questaoAtual - 1),
                $aProximaQuestao = $(aProximaQuestao);

            $liQuestaoAtual.hide('slide', {direction: 'left'}, function(){

                $liQuestaoAtual.next().show('slide', {direction: 'right'});

            });

            $ulAvaliacao.data('questao-atual', ++questaoAtual);

            if ( questaoAtual > 1 ) {

                $aProximaQuestao.parent().prev().show();

            }

            if ( questaoAtual == $liQuestoes.length ) {

                $aProximaQuestao.parent().hide();

            }
        },
        converterTimerParaFracao = function ( $emMin, $emSeg ) {

            var m = parseInt( $emMin.text() ),
                s = parseInt( $emSeg.text().charAt(0) == '0' ? $emSeg.text().charAt(1) : $emSeg.text() );


            return m + (s / 60);
        },
        $divTempoAvaliacaoEsgotado = $(
            '<div id="dialogo-msg-tempo-esgotado">' +
            '<p>O tempo de avaliação se esgotou, o que foi realizado até agora será enviado para correção.<br>' +
                'Achou pouco tempo? Fale com os gerenciadores do curso.</p></div>'
        ),
        decrementarCronometro = function( $emMin, $emSeg ) {

            var m = parseInt( $emMin.text() ),
                s = parseInt( $emSeg.text().charAt(0) == '0' ? $emSeg.text().charAt(1) : $emSeg.text() );

            if ( s == 0 ) {
                m--;
                s = 60;
            }

            s--;

            $emMin.text( m );
            $emSeg.text( s < 10 ? '0' + s : s );

        },
        cronometro = function($emTempoDuracaoM, $emTempoDuracaoS, $formAvaliacao, idInterval){

            var tempo = converterTimerParaFracao(
                $emTempoDuracaoM,
                $emTempoDuracaoS
            );

            if ( tempo <= 0 ) {

                clearInterval( idInterval );

                $divTempoAvaliacaoEsgotado.dialog({
                    title: 'Tempo de Avaliação Esgotado!',
                    width: '450px',
                    resizable: false,
                    modal: true,
                    close: function(){
                        enviarDadosAvaliacao(
                            $emTempoDuracaoM,
                            $emTempoDuracaoS,
                            $formAvaliacao
                        );
                    },
                    buttons: {
                        'Ok' : function() {
                            $( this ).dialog('close');
                        }
                    }
                });

            } else {

                decrementarCronometro($emTempoDuracaoM, $emTempoDuracaoS);

            }

        },
        $divConfirmacaoFinalizacaoAvaliacao = $(
            '<div id="dialogo-confirmacao-finalizar-avaliacao">' +
            '<p>Tem certeza que deseja finalizar a avaliação?<br>' +
                'Verifique se não esqueceu alguma questão em branco, pode ser sua ultima chance.</p></div>'
        ),
        enviarDadosAvaliacao = function($emTempoDuracaoM, $emTempoDuracaoS, $formAvaliacao) {
            var tempoDeProva = converterTimerParaFracao(
                $emTempoDuracaoM,
                $emTempoDuracaoS
            );

            $formAvaliacao.append('<input type="hidden" name="tempoDeProva" value="' + tempoDeProva + '">');

            $.post(
                $formAvaliacao.attr('action'),
                $formAvaliacao.serialize(),
                function(res) {
                    if (res.success) {

                        $divJanelaAvaliacao
                            .html(res.htmlMsgResultado)
                            .dialog('option', 'height', 300)
                            .dialog('option', 'buttons', {
                                'Sair' : function(){
                                    $(this).dialog('close');
                                }
                            });

                    } else {
                        WeLearn.notificar({
                            nivel: 'error',
                            msg: res.errors[0].error_msg,
                            tempo: 5000
                        });
                    }
                }
            );
        },
        finalizarAvaliacao = function($emTempoDuracaoM, $emTempoDuracaoS, $formAvaliacao){
            $divConfirmacaoFinalizacaoAvaliacao.dialog({
                title: 'Tem certeza?',
                width: '450px',
                resizable: false,
                modal: true,
                buttons: {
                    'Confirmar' : function() {

                        enviarDadosAvaliacao($emTempoDuracaoM, $emTempoDuracaoS, $formAvaliacao);

                        $( this ).dialog('close');
                    },
                    'Cancelar' : function() {
                        $( this ).dialog('close');
                    }
                }
            });
        };

    $('a.a-realizar-avaliacao').click(function(e){
        e.preventDefault();

        var $this = $(this),
            idAvaliacao = $this.data('id-avaliacao'),
            url = WeLearn.url.siteURL('/curso/conteudo/aplicacao_avaliacao/aplicar/' + idAvaliacao);

        $.get(
            url,
            {},
            function(res){
                if(res.success) {

                    var $htmlAvaliacao = $( res.htmlAvaliacao ),
                        $emTempoDuracaoM = $htmlAvaliacao.find('#em-tempo-duracao-m'),
                        $emTempoDuracaoS = $htmlAvaliacao.find('#em-tempo-duracao-s'),
                        $formAvaliacao = $htmlAvaliacao.find('#form-aplicacao-avaliacao'),
                        $ulAvaliacao = $htmlAvaliacao.find('#ul-aplicacao-avaliacao'),
                        $liQuestoes =  $ulAvaliacao.children('li.li-questao-exibir-questao'),
                        $ulAlternativas = $liQuestoes.find('ul.selectable-radios'),
                        $navQuestoes = $htmlAvaliacao.find('#nav-navegacao-questoes-avaliacao > ul > li > a'),
                        $aQuestaoAnterior = $navQuestoes.first(),
                        $aProximaQuestao = $navQuestoes.last();

                    var idInterval = setInterval(function(){

                        cronometro( $emTempoDuracaoM, $emTempoDuracaoS, $formAvaliacao, idInterval );

                    }, 1000);

                    $ulAvaliacao.data('questao-atual', 1);

                    $aQuestaoAnterior
                        .bind('click', function(e){
                            e.preventDefault();

                            exibirQuestaoAnterior( $liQuestoes, this );
                        })
                        .parent()
                        .hide();

                    $aProximaQuestao.bind('click', function(e){
                        e.preventDefault();

                        exibirProximaQuestao( $liQuestoes, this );
                    });

                    $liQuestoes
                        .hide()
                        .first()
                        .show();

                    WeLearn.helpers.initSelectableRadios( $ulAlternativas );

                    $divJanelaAvaliacao
                        .html($htmlAvaliacao)
                        .dialog('option', 'height', $window.height() - 6)
                        .dialog('option', 'buttons', {
                            'Finalizar': function(){
                                finalizarAvaliacao( $emTempoDuracaoM, $emTempoDuracaoS, $formAvaliacao );
                            }
                        })
                        .dialog('option', 'close', function(){
                            clearInterval( idInterval );
                            window.location.reload();
                        })
                        .dialog('open');

                } else {

                    WeLearn.notificar({
                        nivel: 'error',
                        msg: res.errors[0].error_msg,
                        tempo: 10000
                    });

                }
            }
        );
    });

    $('a.a-exibir-resultados-avaliacao').click(function(e){
        e.preventDefault();

        var $this = $(this),
            idAvaliacao = $this.data('id-avaliacao'),
            url = WeLearn.url.siteURL('/curso/conteudo/aplicacao_avaliacao/exibir_resultados/' + idAvaliacao);

        $.get(
            url,
            {},
            function(res){
                if(res.success) {

                    $divJanelaAvaliacao
                        .html(res.htmlResultado)
                        .dialog('option', 'height', $window.height() - 6)
                        .dialog('option', 'buttons', {
                            'sair': function(){
                                $(this).dialog('close');
                            }
                        })
                        .dialog('open');

                } else {

                    WeLearn.notificar({
                        nivel: 'error',
                        msg: res.errors[0].error_msg,
                        tempo: 10000
                    });

                }
            }
        );
    });

})();