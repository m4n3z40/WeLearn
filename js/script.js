/* Author:

*/

window.WeLearn = {
    url: {
        params: {},
        queryString: '',
        baseURL: 'http://welearn.com',
        siteURL: function (uri) {
            if ( ! uri ) {
                uri = '';
            }
            uri = uri.replace(/(^\/)|(\/$)/, '');
            return this.baseURL + '/' + uri;
        }
    },
    helpers: {
    },
    notificar : function(opcoes) {
        var classeBarra,
            opcoesPadrao,
            divContainerNotificacoes,
            $divContainerNotificacoes,
            $divNotificacao;

        opcoesPadrao = {
            msg: 'Isto é uma barra de notificação, uma mensagem deveria aparecer aqui.',
            nivel: 'aviso',
            fechavel: true,
            textoFechar: 'X',
            tempo: false,
            redirecionarAoFechar: false,
            redirecionarParaUrl: null
        };

        opcoes = $.extend(opcoesPadrao, opcoes);

        divContainerNotificacoes = document.getElementById('container-notificacoes');
        if (divContainerNotificacoes == null) {
            divContainerNotificacoes = document.createElement('div');
            divContainerNotificacoes.setAttribute('id', 'container-notificacoes');
            $('body').prepend(divContainerNotificacoes);
        }
        $divContainerNotificacoes = $(divContainerNotificacoes);

        switch ( opcoes.nivel ) {
            case 'erro': classeBarra = 'barra-notificacao-erro'; break;
            case 'sucesso': classeBarra = 'barra-notificacao-sucesso'; break;
            case 'aviso': classeBarra = 'barra-notificacao-aviso'; break;
            default: classeBarra = 'barra-notificacao-aviso';
        }

        var fechar = function(notificacao) {
            notificacao.slideUp(function(){
                $(this).remove();

                if( opcoes.redirecionarAoFechar && opcoes.redirecionarParaUrl != null ) {
                    window.location = opcoes.redirecionarParaUrl;
                }
            });
        };

        $divNotificacao = $(document.createElement('div'));

        var $botaoFechar = $('<span />');
        if ( opcoes.fechavel ) {
            $botaoFechar.addClass('barra-notificacao-fechar-habilitado')
                        .html('<a href="#">' + opcoes.textoFechar + '</a>')
                        .find('a')
                            .bind('click', function(e){ e.preventDefault(); fechar($divNotificacao); });
        } else {
            $botaoFechar.addClass('barra-notificacao-fechar-desabilitado');
        }

        $divNotificacao.css({display: 'none'})
                       .addClass('wrapper-notificacao')
                       .addClass(classeBarra)
                       .html('<div class="msg-notificacao">' + opcoes.msg + '</div>')
                       .append($botaoFechar)
                       .appendTo($divContainerNotificacoes)
                       .slideDown();

        if( opcoes.tempo ) {
            setTimeout(function(){
                fechar($divNotificacao);
            }, opcoes.tempo)
        }
    },
    validarForm : function(form, url_validacao, onValidationPass, onValidationFail) {
        var $form = $(form);
        var processarResultado = function(result) {
            if ( ! result.success ) {
                var erro,
                    elems;

                for (var i = 0; i < result.errors.length; i++) {
                    erro = result.errors[i];

                    if (erro.field_name == "noField") {
                        WeLearn.notificar({msg: erro.error_msg, nivel: 'erro', tempo: 10000});
                        continue;
                    }

                    elems = 'input[name=' + erro.field_name + '],' +
                            'select[name=' + erro.field_name + '],' +
                            'textarea[name=' + erro.field_name + ']';

                    var $campos = $(elems);

                    $campos.after(
                        '<p class="validation-error">' + erro.error_msg + '</p>'
                    );

                    $campos.change(function(evt){
                        $(this).next('p.validation-error').remove();
                    });
                }

                if (onValidationFail) {
                   onValidationFail(result);
                }
            } else {
                if (onValidationPass) {
                    onValidationPass(result);
                }
            }
        };

        $('.validation-error, .error').remove();

        $.post(
            url_validacao,
            $form.serialize(),
            processarResultado,
            'json'
        );
    },
    segmento : {
        gerarOpcoesHTML : function(segmentosJSON) {
            var html = '';

            for(var i = 0; i < segmentosJSON.length; i++) {
                html = html + '<option value="' + segmentosJSON[i].id + '">' + segmentosJSON[i].descricao + '</option>';
            }
            html = html + '<option value="0" selected="selected">Selecione um segmento desta área</option>';

            return html;
        }
    },
    initAjax : function () {
        var loaderHTML = '<div id="ajax-loading">' +
                         '<img src="http://welearn.com/img/ajax_loading.gif" alt="Carregando..." />' +
                         '</div>';

        var $loader = $(loaderHTML);

        $loader.bind({
            ajaxStart: function(){
                $(this).show('slide', {direction: 'up'}, 250);
            },
            ajaxStop: function(){
                $(this).hide('slide', {direction: 'up'}, 250);
            }
        });

        $('body').prepend($loader);
    },
    initNotificacoes: function () {
        if (typeof flashData != 'undefined' && flashData != false) {
            if ($.isArray(flashData)) {
                for(var i = 0; i < flashData.length; i++) {
                    this.notificar(flashData[i]);
                }
            } else {
                    this.notificar(flashData);
            }
        }
    },
    initUrl: function () {
        var e,
            a = /\+/g,
            r = /([^&=]+)=?([^&]*)/g,
            d = function (s) { return decodeURIComponent(s.replace(a, " ")); },
            q = window.location.search.substring(1),
            url = window.location.href,
            queryString = url.split('?')[1];

        while (e = r.exec(q))
           this.url.params[d(e[1])] = d(e[2]);

        if (queryString) {
            this.url.queryString = queryString;
        }
    },
    init : function(){
        this.initUrl();
        this.initAjax();
        this.initNotificacoes();
    }
};

$(document).ready(function(){
   WeLearn.init();
});