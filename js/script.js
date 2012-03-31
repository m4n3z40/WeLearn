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
        accordionToURLParamPosicoes: function ($accordion) {
            var arrayPosicoes = $accordion.sortable('toArray'),
                i,
                parametrosGet = {};

            for (i = 1; i <= arrayPosicoes.length; i++) {
                parametrosGet[ arrayPosicoes[i - 1] ] = i;
            }

            return $.param(parametrosGet);
        }
    },
    notificar : function(opcoes) {
        var opcoesPadrao = {
                msg: 'Isto é uma barra de notificação, uma mensagem deveria aparecer aqui.',
                nivel: 'alert',
                fechavel: true,
                tempo: false,
                redirecionarAoFechar: false,
                redirecionarParaUrl: null
            },
            opcoesNoty;

        opcoes = $.extend(opcoesPadrao, opcoes);

        opcoesNoty = {
            text: opcoes.msg,
            type: opcoes.nivel,
            closeOnSelfClick: opcoes.fechavel,
            timeout: opcoes.tempo
        }

        if ( opcoes.redirecionarAoFechar ) {
            opcoesNoty.onClose = function(){
                window.location = opcoes.redirecionarParaUrl;
            };
        }

        $.noty(opcoesNoty);
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
                        WeLearn.notificar({msg: erro.error_msg, nivel: 'error', tempo: 10000});
                        continue;
                    }

                    elems = 'input[name=' + erro.field_name + '],' +
                            'select[name=' + erro.field_name + '],' +
                            'textarea[name=' + erro.field_name + ']';

                    var $campos = $(elems);

                    if( $campos.length > 0 ) {
                        $campos.after(
                            '<p class="validation-error">' + erro.error_msg + '</p>'
                        );

                        $campos.change(function(evt){
                            $(this).next('p.validation-error').remove();
                        });
                    } else {
                        WeLearn.notificar({
                            msg: erro.error_msg,
                            nivel: 'error',
                            tempo: 10000
                        });
                    }
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

        $.datepicker.setDefaults({
            closeText:"Pronto",
            prevText:"Ant.",
            nextText:"Próx.",
            currentText:"Hoje",
            monthNames:["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
            monthNamesShort:["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
            dayNames:["Domingo","Segunda","terça","Quarta","Quinta","Sexta","Sábado"],
            dayNamesShort:["Dom","Seg","Ter","Qua","Qui","Sex","Sab"],
            dayNamesMin:["Do","Se","Te","Qu","Qu","Se","Sa"],
            weekHeader:"Sem",
            dateFormat:"dd/mm/yy",
            firstDay:0,
            isRTL:false,
            showMonthAfterYear:false,
            yearSuffix:""
        });
    }
};

WeLearn.init();