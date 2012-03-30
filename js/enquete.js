(function () {
    var gerarHTMLAlternativa = function(n) {
            return "<li>" +
                   "<input type='text' name='alternativas[]' id='txt-alternativa-enquete-" + n +
                   "' placeholder='Entre com a alternativa " + n + "'>" +
                   "</li>";
        },
        $olCriarEnqueteAlternativas = $('#ol-criar-enquete-alternativas'),
        $h4MsgNenhumaAlternativa = $('<h4>Nenhuma alternativa foi adicionada nesta enquete.</h4>');

    if ( $olCriarEnqueteAlternativas.children().length <= 0 ) {
        $olCriarEnqueteAlternativas.before( $h4MsgNenhumaAlternativa );
    }
    
    $('#btn-adicionar-alternativa').click(function(e){
        e.preventDefault();

        var qtdAlternativas = $olCriarEnqueteAlternativas.children().length;

        if ( qtdAlternativas >= 10 ) {
            $('<div id="dialogo-aviso-adicionar-alternativa-enquete""><p>Número máximo de alternativas alcançado! <br>' +
              ' Não será possível adicionar mais alternativas.</p></div>')
             .dialog({
                title: 'Ação inválida!',
                width: '450px',
                modal: true,
                resizable: false,
                buttons: {
                    Ok : function () {
                        $(this).dialog('close');
                    }
                }
            });

            return;
        }

        var nAltenativaAtual = qtdAlternativas + 1,
            $alternativa = $( gerarHTMLAlternativa(nAltenativaAtual) );

        $olCriarEnqueteAlternativas.prev('h4').remove();

        $olCriarEnqueteAlternativas.append($alternativa);
    });

    $('#btn-remover-alternativa').click(function(e){
        e.preventDefault();

        if ( $olCriarEnqueteAlternativas.children().length > 0 ) {
            $olCriarEnqueteAlternativas.children().last().remove();

            if ($olCriarEnqueteAlternativas.children().length == 0 ) {
                $olCriarEnqueteAlternativas.before( $h4MsgNenhumaAlternativa );
            }
        }
    });

    $('#txt-data-expiracao').datepicker({
        minDate: '+1D',
        maxDate: '+1Y',
        defaultDate: '+1',
        showOn: 'both',
        showAnim: 'fadeIn'
    });

    var formEnquete = document.getElementById('form-criar-enquete') ||
                      document.getElementById('form-alterar-enquete'),
        alterandoEnquete = ( $(formEnquete).attr('id') == 'form-alterar-enquete' );

    if ( alterandoEnquete ) {
        var $divConfirmacaoAlterar = $('<div id="dialogo-confirmacao-remover-enquete">' +
                                       '<p>Tem certeza que deseja alterar os dados desta enquete?<br/>' +
                                       'Ao fazer isso <strong>TODAS</strong> as participações de ' +
                                       'usuários contidas nesta enquete serão ' +
                                       '<strong>PERDIDAS PARA SEMPRE!</strong></p></div>');
    }

    $('#btn-form-enquete').click(function(e){
        e.preventDefault();

        var url = WeLearn.url.siteURL('enquete/enquete/salvar');

        if ( alterandoEnquete ) {
            $divConfirmacaoAlterar.dialog({
                title: 'Tem certeza?',
                width: '450px',
                resizable: false,
                modal: true,
                buttons: {
                    'Confirmar' : function() {
                        WeLearn.validarForm(formEnquete, url, function(res) {
                            window.location = WeLearn.url.siteURL('curso/enquete/exibir/' + res.idEnquete);
                        });

                        $( this ).dialog('close');
                    },
                    'Cancelar' : function() {
                        $( this ).dialog('close');
                    }
                }
            });
        } else {
            WeLearn.validarForm(formEnquete, url, function(res) {
                window.location = WeLearn.url.siteURL('curso/enquete/exibir/' + res.idEnquete);
            });
        }
    });

    var $listaEnqueteDataTable = $('#enquete-listar-datatable');

    $('#paginacao-enquete').children('a').click(function(e){
        e.preventDefault();

        var $this = $(this),
            proximo = $this.data('proximo'),
            idCurso = $this.data('id-curso'),
            url = WeLearn.url.siteURL('enquete/enquete/proxima_pagina/' + idCurso + '/' + proximo);

        $.get(
            url,
            (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
            function(res) {
                if (res.success) {
                    $listaEnqueteDataTable.append(res.htmlListaEnquetes);

                    if (res.paginacao.proxima_pagina) {
                        $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                    } else {
                        $this.parent().html('<h4>Não há mais enquetes a ' +
                                            'serem exibidas.</h4>');
                    }
                } else {
                    WeLearn.notificar({
                        msg: res.errors[0].error_msg,
                        nivel: 'error',
                        tempo: 10000
                    });
                }
            },
            'json'
        )
    });

    var visualizandoListaEnquetes = (document.getElementById('enquete-listar-content') != null),
        $divConfirmacaoRemover = $('<div id="dialogo-confirmacao-remover-enquete">' +
                                   '<p>Tem certeza que deseja remover esta enquete?<br/>' +
                                   'Esta ação <strong>NÃO</strong> poderá ser desfeita!<br/>' +
                                   '<strong>TODAS</strong> as participações de usuários nesta ' +
                                   'enquete serão <strong>PERDIDAS!</strong></p></div>');

    $('a.a-enquete-remover').live('click', function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoRemover.dialog({
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
                                if (visualizandoListaEnquetes) {
                                    $this.parent().parent().parent().parent().parent().fadeOut('slow', function(){
                                        $( this ).remove();
                                    });
                                } else {
                                    res.notificacao.redirecionarAoFechar = true;
                                    res.notificacao.redirecionarParaUrl = WeLearn.url.siteURL('/curso/enquete/' + res.idCurso);
                                    res.notificacao.tempo = 1000;
                                }

                                WeLearn.notificar(res.notificacao);
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
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

    var $divConfirmacaoAlterarStatus = $('<div id="dialogo-confirmacao-alterarstatus-enquete">' +
                                         '<p>Tem certeza que deseja alterar o status desta enquete?<br/>' +
                                         'Enquetes <strong>Inativas</strong> não serão visíveis aos alunos.</p></div>');

    $('a.a-enquete-alterarstatus').live('click', function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoAlterarStatus.dialog({
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
                                WeLearn.notificar(res.notificacao);

                                if (visualizandoListaEnquetes) {
                                    $this.parent().parent().parent().parent().parent().fadeOut('slow', function(){
                                        $( this ).remove();
                                    });
                                } else {
                                    if (res.statusAtual == 'ativada') {
                                        $this.text('Desativar');
                                    } else {
                                        $this.text('Ativar');
                                    }
                                }
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
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

    var $divConfirmacaoAlterarSituacao = $('<div id="dialogo-confirmacao-alterarsituacao-enquete">' +
                                           '<p>Tem certeza que deseja alterar a situação desta enquete?<br/>' +
                                           'Ao <strong>Fechar</strong> uma enquete, esta não poderá mais receber participações.</p></div>');

    $('a.a-enquete-alterarsituacao').live('click', function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoAlterarSituacao.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        $this.attr('href'),
                        visualizandoListaEnquetes ? {} : {'exibindoEnquete': 1},
                        function(res) {
                            if (res.success) {
                                if (visualizandoListaEnquetes) {
                                    WeLearn.notificar(res.notificacao);

                                    $this.parent().parent().parent().parent().parent().fadeOut('slow', function(){
                                        $( this ).remove();
                                    });
                                } else {
                                    window.location.reload();
                                }
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
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

    var $ulListaAlternativas = $('#ul-enquete-alternativas');
    $ulListaAlternativas.find('input[type=radio]').hide();
    $ulListaAlternativas.selectable({
        tolerance: 'fit',
        start: function(e, ui) {
            var $selected = $(this).children('li.ui-selected');
            $selected.find('input[type=radio]').removeAttr('checked');
            $selected.removeClass('ui-selected');
        },
        stop: function(e, ui) {
            $(this).children('li.ui-selected').first().find('input[type=radio]').attr('checked', true);
        }
    });

    var $formVotar = $(document.getElementById('form-enquete-votar'));
    $('#btn-votar-enquete').click(function(e){
        e.preventDefault();

        $.post(
            $formVotar.attr('action'),
            $formVotar.serialize(),
            function (res) {
                if (res.success) {
                    window.location.reload();
                } else {
                    WeLearn.notificar({
                        'nivel': 'error',
                        'msg': res.errors[0].error_msg,
                        'tempo': 5000
                    });
                }
            }
        );
    });
})();