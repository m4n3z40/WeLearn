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

    var formEnquete = document.getElementById('form-criar-enquete');

    $('#btn-form-enquete').click(function(e){
        e.preventDefault();

        var url = WeLearn.url.siteURL('enquete/enquete/salvar');

        WeLearn.validarForm(formEnquete, url, function(res) {
            window.location = WeLearn.url.siteURL('curso/enquete/exibir/' + res.idEnquete);
        });
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
                        $this.parent().html('<h4>Não há mais enquetes a serem exibidas.</h4>')
                    }
                } else {
                    WeLearn.notificar({
                        msg: res.errors[0].error_msg,
                        nivel: 'erro',
                        tempo: 15000
                    });
                }
            },
            'json'
        )
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
})();