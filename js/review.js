(function(){
    var $nbrQualidade = $('#nbr-qualidade'),
        $divSliderQualidade = $('#div-slider-qualidade'),
        $nbrDificuldade = $('#nbr-dificuldade'),
        $divSliderDificuldade = $('#div-slider-dificuldade'),
        atualizarSliderQualidade = function(){
            $divSliderQualidade.slider('value', parseInt( $(this).val() ) );
        },
        atualizarSliderDificuldade = function(){
            $divSliderDificuldade.slider('value', parseInt( $(this).val() ) );
        };

    $divSliderQualidade.slider({
        value: parseInt( $nbrQualidade.val() ),
        min: $nbrQualidade.attr('min'),
        max: $nbrQualidade.attr('max'),
        step: 1,
        slide: function(e, ui) {
            $nbrQualidade.val( ui.value );
        }
    });

    $nbrQualidade.change(atualizarSliderQualidade);
    $nbrQualidade.click(atualizarSliderQualidade);
    
    $divSliderDificuldade.slider({
        value: parseInt( $nbrDificuldade.val() ),
        min: $nbrDificuldade.attr('min'),
        max: $nbrDificuldade.attr('max'),
        step: 1,
        slide: function(e, ui) {
            $nbrDificuldade.val( ui.value );
        }
    });

    $nbrDificuldade.change(atualizarSliderDificuldade);
    $nbrDificuldade.click(atualizarSliderDificuldade);

    var formResenha = document.getElementById('form-review-criar') ||
                      document.getElementById('form-review-alterar');

    $('#btn-form-review').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formResenha,
            $(formResenha).attr('action'),
            function(res) {
                window.location = WeLearn.url.siteURL('/curso/review/' + res.idCurso);
            }
        )
    });

    var $emQtdReviews = $('#em-qtd-reviews'),
        $emTotalReviews = $('#em-total-reviews'),
        $ulListaReviews = $('#ul-lista-reviews'),
        $divFormResposta = $(
            '<div/>',
            {id : 'div-dialogo-form-resposta-review'}
        ).dialog({
            autoOpen: false,
            resizable: false,
            width: 610,
            height: 500,
            beforeClose: function(){
                $(this).empty();
            },
            title: 'Resposta à Avaliação'
        });

    $('#a-proxima-pagina').click(function(e){
        e.preventDefault();

        var $this = $(this),
            url = WeLearn.url.siteURL('/curso/review/recuperar_proxima_pagina/'
                                      + $this.data('id-curso') + '/'
                                      + $this.data('proximo'));

        $.get(
            url,
            {},
            function(res) {
                if (res.success) {
                    $ulListaReviews.append(res.htmlListaReviews);
                    $emQtdReviews.text( parseInt( $emQtdReviews.text() ) + res.qtdReviews );

                    if (res.paginacao.proxima_pagina) {
                        $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                    } else {
                        $this.replaceWith('<h4>Não há mais avaliações para serem listadas.</h4>');
                    }
                } else {
                    WeLearn.notificar({
                        nivel: 'error',
                        msg: res.errors[0].error_msg,
                        tempo: 5000
                    });
                }
            }
        )
    });

    var $divConfirmacaoRemoverReview = $('<div id="dialogo-confirmacao-remover-review">' +
                                         '<p>Tem certeza que deseja remover esta avaliação?<br/>' +
                                         'Esta ação <strong>NÃO</strong> poderá ser desfeita!</div>');
    $('a.a-remover-review').live('click', function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoRemoverReview.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        $this.attr('href'),
                        {},
                        function(res) {
                            if (res.success) {
                                $divConfirmacaoRemoverReview.dialog('close');
                                $this.parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                    .fadeOut(function(){
                                        $(this).remove();

                                        $emQtdReviews.text( parseInt( $emQtdReviews.text() ) - 1 );
                                        $emTotalReviews.text( parseInt( $emTotalReviews.text() - 1 ) );
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
                },
                'Cancelar' : function() {
                    $( this ).dialog('close');
                }
            }
        });
    });

    $('a.a-responder-review').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            $divResposta = $this.parent()
                                .parent()
                                .parent()
                                .parent()
                                .prev();

        $.get(
            $this.attr('href'),
            {},
            function(res) {
                if (res.success) {
                    $divFormResposta.html(res.htmlFormResponder)
                        .dialog('option', 'buttons', {
                            'Salvar Resposta!': function(){
                                var formCriarResposta = document.getElementById('form-resposta-review-criar');

                                WeLearn.validarForm(
                                    formCriarResposta,
                                    $(formCriarResposta).attr('action'),
                                    function(res) {
                                        if (res.success) {
                                            $divFormResposta.dialog('close');

                                            $divResposta.html(res.htmlResposta)
                                                        .fadeIn();

                                            WeLearn.notificar(res.notificacao);

                                            $this.parent().remove();
                                        } else {
                                            WeLearn.notificar({
                                                nivel: 'error',
                                                msg: res.errors[0].error_msg,
                                                tempo: 5000
                                            });
                                        }
                                    }
                                )
                            },
                            'Cancelar': function(){
                                $(this).dialog('close');
                            }
                        })
                        .dialog('open');
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

    $('a.a-alterar-resposta').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            $divResposta = $this.parent()
                                .parent()
                                .parent()
                                .parent()
                                .parent();

        $.get(
            $this.attr('href'),
            {},
            function(res) {
                if (res.success) {
                    $divFormResposta.html(res.htmlFormAlterarResposta)
                        .dialog('option', 'buttons', {
                            'Salvar Resposta!': function(){
                                var formAlterarResposta = document.getElementById('form-resposta-review-alterar');

                                WeLearn.validarForm(
                                    formAlterarResposta,
                                    $(formAlterarResposta).attr('action'),
                                    function(res) {
                                        if (res.success) {
                                            $divFormResposta.dialog('close');

                                            $divResposta.html(res.htmlResposta)
                                                        .fadeIn();

                                            WeLearn.notificar(res.notificacao);
                                        } else {
                                            WeLearn.notificar({
                                                nivel: 'error',
                                                msg: res.errors[0].error_msg,
                                                tempo: 5000
                                            });
                                        }
                                    }
                                )
                            },
                            'Cancelar': function(){
                                $(this).dialog('close');
                            }
                        })
                        .dialog('open');

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

    $('a.a-remover-resposta').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            $divResposta = $this.parent()
                                .parent()
                                .parent()
                                .parent()
                                .parent(),
            $ulMenuGerenciamento = $divResposta.next().find('ul');

        $.get(
            $this.attr('href'),
            {},
            function(res) {
                if (res.success) {
                    $divResposta.fadeOut(function(){
                        $divResposta.empty();
                        $ulMenuGerenciamento.append('<li>' + res.htmlLinkResponder + '</li>');
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