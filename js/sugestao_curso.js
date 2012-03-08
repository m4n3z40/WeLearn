/**
 * Created by JetBrains PhpStorm.
 * User: Allan Marques (allan.marques@ymail.com)
 * Date: 10/09/11
 * Time: 10:15
 * To change this template use File | Settings | File Templates.
 */

(function(){
    var btnSugerir = document.getElementById('btn-sugerir');
    if(btnSugerir != null) {
        var $btnSugerir = $('#btn-sugerir');

        $btnSugerir.click(function(e){
            e.preventDefault();
            var $form = $('#form-sugestao'),
                urlValidacao = $form.attr('action'),
                $sltsSegmentos = $form.find('#slt-area option:selected,#slt-segmento option:selected');

            $sltsSegmentos.each(function(){
                if( $(this).val() == '0' ) {
                    $(this).val('');
                }
            });

            WeLearn.validarForm(
                $form,
                urlValidacao,
                function(res) {
                    window.location = WeLearn.url.siteURL('curso/sugestao');
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

        var url = WeLearn.url.siteURL('curso/sugestao/listar'), //window.location.toString().split('?')[0];
            filtro = WeLearn.url.params.f,
            queryString;
        $sltArea.unbind('change')
                .change(function(e){
                    if ( $(this).val() != '0' ) {
                        switch(filtro) {
                            case 'pop':
                                queryString = '?f=pop';
                            break;
                            case 'acc':
                                queryString = '?f=acc';
                            break;
                            default:
                                queryString = '?f=are';
                        }

                        window.location = url + queryString + '&a=' + $(this).val();
                    }
                });

        $sltSegmento.unbind('change')
                .change(function(e){
                    if ( $sltArea.val() != '0' && $(this).val() != '0' ) {
                        switch(filtro) {
                            case 'pop':
                                queryString = '?f=pop';
                            break;
                            case 'acc':
                                queryString = '?f=acc';
                            break;
                            default:
                                queryString = '?f=seg';
                        }

                        window.location = url + queryString + '&a=' + $sltArea.val() + '&s=' + $(this).val();
                    }
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
                WeLearn.url.siteURL('curso/sugestao/proxima_pagina/' + $aBtnProxPagina.data('proximo')),
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

    var $aVotarSugestao = $('.votar-sugestao > a');
    if ($aVotarSugestao != []) {
        $aVotarSugestao.live('click', function(e){
            e.preventDefault();

            var $spnQtdVotos = $(this).parent().parent().find('.qtd-votos > span'),
                idSugestao = $(this).data('id-sugestao'),
                url = WeLearn.url.siteURL('curso/sugestao/votar/' + idSugestao),
                processar = function (res) {
                    if (res.success) {
                        WeLearn.notificar({
                            msg: 'Seu voto foi registrado com sucesso! Aguarde, quando esta sugestão gerar um curso, <br/>' +
                                 'Você será avisado!',
                            tempo: 10000,
                            nivel: 'sucesso'
                        });

                        $spnQtdVotos.text(res.qtdVotos);
                    } else {
                        WeLearn.notificar({
                            msg: res.errors[0].error_msg,
                            tempo: 10000,
                            nivel: 'erro'
                        });
                    }
                };

            $.get(url, {}, processar);
        });
    }
})();