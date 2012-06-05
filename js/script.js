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
        initSelectableRadios: function($selectable) {
            $selectable.find('input[type=radio]').hide();
            $selectable.selectable({
                tolerance: 'fit',
                start: function(e, ui) {
                    var $selected = $(this).children('li.ui-selected');
                    $selected.find('input[type=radio]').removeAttr('checked');
                    $selected.removeClass('ui-selected');
                },
                stop: function(e, ui) {
                    $(this).children('li.ui-selected').first()
                                                      .find('input[type=radio]')
                                                      .attr('checked', true);
                }
            });
        },
        initAllSelectablesRadios: function() {
            this.initSelectableRadios( $('ul.selectable-radios') );
        },
        sortableToURLParamPosicoes: function ($sortable) {
            var arrayPosicoes = $sortable.sortable('toArray'),
                i,
                parametrosGet = {};

            for (i = 1; i <= arrayPosicoes.length; i++) {
                parametrosGet[ arrayPosicoes[i - 1] ] = i;
            }

            return $.param(parametrosGet);
        },
        objectListToSelectOptions: function (objList) {
            if ( ! $.isArray(objList) ) { return ''; }

            var htmlOptions = '',
                i,
                obj;

            for (i = 0; i < objList.length; i++) {
                obj = objList[i];

                if ( obj.hasOwnProperty('name') && obj.hasOwnProperty('value') ) {
                    htmlOptions += '<option value="' + obj.value + '">' + obj.name + '</option>'
                }
            }

            return htmlOptions;
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

        opcoes.msg += '<br><br><span>( Clique nesta notificação para fechá-la )</span>';

        opcoesNoty = {
            text: opcoes.msg,
            type: opcoes.nivel,
            closeOnSelfClick: opcoes.fechavel,
            timeout: opcoes.tempo
        };

        if ( opcoes.redirecionarAoFechar ) {
            opcoesNoty.onClose = function(){
                window.location = opcoes.redirecionarParaUrl;
            };
        }

        $.noty(opcoesNoty);
    },
    exibirErros : function (errors) {
        var erro,
            elems;

        for (var i = 0; i < errors.length; i++) {
            erro = errors[i];

            if (erro.field_name == "noField") {
                WeLearn.notificar({msg: erro.error_msg, nivel: 'error', tempo: 5000});
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
                    tempo: 5000
                });
            }
        }
    },
    validarForm : function(form, url_validacao, onValidationPass, onValidationFail) {
        var $form = $(form);
        var processarResultado = function(result) {
            if ( ! result.success ) {

                WeLearn.exibirErros( result.errors );

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
                         '<img src="' + this.url.siteURL('/img/ajax_loading.gif') + '" alt="Carregando..." />' +
                         '</div>';

        var $loader = $(loaderHTML);

        $loader.bind({
            ajaxStart: function(){
                $(this).show();
            },
            ajaxStop: function(){
                $(this).hide();
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
    bindDefaultEvents : function (){
        var $txtSearch = $('#txt-search');

        $('#btn-submit-search').click(function(e){
            if ( ! $txtSearch.val() ) {
                e.preventDefault();
            }
        });

    },
    init : function(){
        this.initUrl();
        this.initAjax();
        this.initNotificacoes();
        this.bindDefaultEvents();

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