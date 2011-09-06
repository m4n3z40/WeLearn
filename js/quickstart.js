/**
 * Created by JetBrains PhpStorm.
 * User: Allan Marques
 * Date: 26/08/11
 * Time: 14:38
 */

$(document).ready(function(){
    var $barraProgresso = $('#progressbar'),
        $etapasContainer = $('#quickstart-form-container');

    $barraProgresso.progressbar({value: 0});

    function carregarEtapa (etapa) {
        $.get(
            'http://welearn.com/quickstart/carregar_etapa/' + etapa,
            null,
            function(res) {
                exibirEtapa(etapa, res);
                anexarJSEtapa(etapa);
            }
        );
    }

    function salvarEtapa (etapa, dados) {
        $.post(
            'http://welearn.com/quickstart/salvar_etapa/' + etapa,
            dados,
            function(res) {
                if(res.success) {
                    exibirProximaEtapa(etapa);
                } else {
                    alert('Erro no salvamento!');
                }
            },
            'json'
        );
    }

    function pularEtapaAtual() {
        var $etapasContainer = $('.container-etapa'),
            etapaAtual = parseInt($etapasContainer.attr('id').split('-')[1]);

        exibirProximaEtapa(etapaAtual);
    }

    function finalizarQuickstart() {
        alert('Quickstart Finalizado!');
        window.location = 'http://welearn.com/home';
    }

    function exibirEtapa (etapa, conteudoEtapa) {
        var $containerEtapa = $( document.createElement('div') ),
            $tituloProgresso = $('#quickstart-progress > h3'),
            numeroEtapas = 5,
            progressoAtual = (100 / numeroEtapas) * etapa;

        $tituloProgresso.html('Etapa ' + etapa + ' de ' + numeroEtapas);
        $barraProgresso.progressbar('option', 'value', progressoAtual);

        $containerEtapa.addClass('container-etapa');
        $containerEtapa.attr('id', 'etapa-' + etapa);
        $containerEtapa.append(conteudoEtapa);
        $etapasContainer.prepend($containerEtapa);
        $containerEtapa.show('slide', { direction: 'right' }, 500);
    }

    function exibirProximaEtapa (etapaAtual) {
        var $containerEtapa = $('#etapa-' + etapaAtual),
            $jsEtapa = $('#js-etapa-' + etapaAtual),
            proximaEtapa = ++etapaAtual;

        if (proximaEtapa > 5) {
            finalizarQuickstart();
            return;
        }

        if($jsEtapa.length > 0) {
            $jsEtapa.remove();
        }

        if( $containerEtapa.length > 0 ) {
            $containerEtapa.hide('slide', {}, 500, function(){
                setTimeout(function(){
                    $(this).remove()
                }, 1000);
            });
        }

        carregarEtapa(proximaEtapa);
    }

    function anexarJSEtapa(etapa) {
        var js;

        switch(etapa) {
            case 1:
                js = 'dados_pessoais.js'; break;
            case 2:
                js = 'dados_profissionais.js'; break;
            default:
                js = false;
        }

        if(js !== false) {
            $('body').append('<script src="http://welearn.com/js/' + js + '" id="js-etapa-' + etapa + '"></script> ');
        }
    }

    function init() {
        var etapaAtual = 0;
        exibirProximaEtapa(etapaAtual);

        var $btnSalvar = $('.salvar'),
            $btnPular = $('.pular'),
            $btnPularTodos = $('.pular-todos');

        $btnSalvar.click(function(e){
            e.preventDefault();

            var frmEtapaAtual = $('.quickstart-form'),
                etapa = parseInt(frmEtapaAtual.attr('id').split('-')[2]);

            salvarEtapa(etapa, frmEtapaAtual.serialize());
        });

        $btnPular.click(function(e){
            e.preventDefault();
            pularEtapaAtual();
        });

        $btnPularTodos.click(function(e){
            e.preventDefault();
            finalizarQuickstart();
        });
    }

    init();
});
