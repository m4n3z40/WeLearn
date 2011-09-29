/**
 * Created by JetBrains PhpStorm.
 * User: Allan Marques (allan.marques@ymail.com)
 * Date: 10/09/11
 * Time: 10:15
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){
    var btnSugerir = document.getElementById('btn-sugerir');
    if(btnSugerir != null) {
        var $btnSugerir = $('#btn-sugerir');

        $btnSugerir.click(function(e){
            e.preventDefault();
            var $form = $('#form-sugestao'),
                urlValidacao = $form.attr('action'),
                $sltsSegmentos = $form.find('#slt-area option:selected,#slt-segmento option:selected');

            $sltsSegmentos.each(function(){
                if( $(this).val() == '0' || $(this).val() == '' ) {
                    $(this).val('');
                }
            });

            WeLearn.validarForm(
                $form,
                urlValidacao,
                function(res) {
                    window.location = 'http://welearn.com/curso/sugestao';
                },
                function(res) {
                    var msg = 'Ocorreram erros ao salvar sua sugestão, verifique o formulário.';

                    WeLearn.notificar({
                        msg: msg,
                        nivel: 'erro',
                        tempo: 10000
                    });
                }
            )
        });
    }

    var divListaSugestoes = document.getElementById('lista-sugestoes');
    if(divListaSugestoes != null) {
        var $divListaSugestoes = $(divListaSugestoes),
            accordionOptions = {collapsible: true, active: false, autoHeight: false};
        $divListaSugestoes.accordion(accordionOptions);

        $('#prox-pagina > a').click(function(e){
            e.preventDefault();


            var filtros = WeLearn.url.queryString,
                $aBtnProxPagina = $(this);

            $.get(
                'http://welearn.com/curso/sugestao/proxima_pagina/' + $aBtnProxPagina.data('proximo'),
                (filtros !== '') ? filtros : null,
                function(res){
                    if (res.success) {
                        var $retorno = $(res.sugestoesHtml);
                        $divListaSugestoes.append($retorno)
                                          .accordion('destroy')
                                          .accordion(accordionOptions);

                        if (res.paginacao.proxima_pagina) {
                            $aBtnProxPagina.data('proximo', res.paginacao.inicio_proxima_pagina);
                        } else {
                            $aBtnProxPagina.parent().prepend('<span>Não há mais Sugestões a serem exibidas.</span>');
                            $aBtnProxPagina.remove();
                        }
                    } else {
                        var msg = 'Ocorreram erros ao retornar a próxima página, Tente nova mais tarde.';

                        WeLearn.notificar({
                            msg: msg,
                            nivel: 'erro',
                            tempo: 10000
                        });
                    }
                }
            );
        });
    }

    var tabFiltros = document.getElementById('tab-filtro');
    if (tabFiltros != null) {
        var $tabFiltros = $(tabFiltros),
            $tabSegmentos = $tabFiltros.find('a[href="#form-segmentos"]'),
            $divSegmentos = $('#form-segmentos'),
            $sltArea = $('#slt-area'),
            $sltSegmento = $('#slt-segmento');

        $tabSegmentos.click(function(e){
            e.preventDefault();

            $divSegmentos.toggle('fast');
        });

        var url = window.location.toString().split('?')[0];
        $sltArea.unbind('change')
                .change(function(e){
                    if ( $(this).val() != '0' )
                        window.location = url + '?f=are&a=' + $(this).val();
                });

        $sltSegmento.unbind('change')
                .change(function(e){
                    if ( $sltArea.val() != '0' && $(this).val() != '0' )
                        window.location = url + '?f=seg&a=' + $sltArea.val() + '&s=' + $(this).val();
                });
    }
});