(function(){
    var $barraProgresso = $('#progressbar'),
        $h3EtapaProgresso = $('#quickstart-progress > h3'),
        $etapaDadosPessoais = $('#etapa-dados-pessoais'),
        $etapaDadosProfissionais = $('#etapa-dados-profissionais'),
        $etapaUploadImagem = $('#etapa-upload-imagem'),
        $etapaConfigPrivacidade = $('#etapa-configuracao-privacidade'),
        $aSalvarEtapa = $('.quickstart-salvar'),
        $aPularEtapa = $('.quickstart-pular'),
        $aPularTodasEtapas = $('.quickstart-pular-todos'),
        etapasQuickstart = [
            $etapaDadosPessoais,
            $etapaDadosProfissionais,
            $etapaUploadImagem,
            $etapaConfigPrivacidade
        ],
        $etapaAnterior = null,
        $etapaAtual = null,
        totalEtapas = etapasQuickstart.length,
        numEtapaAtual = 0;

    $barraProgresso.progressbar({value: 0});

    function salvarEtapaAtual() {
        var $formEtapaAtual = $etapaAtual.find('form');

        WeLearn.validarForm(
            $formEtapaAtual,
            $formEtapaAtual.attr('action'),
            function(res) {
                if (res.notificacao) {
                    WeLearn.notificar( res.notificacao );
                }

                exibirProximaEtapa();
            }
        );
    }

    function exibirEtapaAtual() {
        $('html, body').animate({ scrollTop: 0 }, 'fast');

        if ( $etapaAnterior ) {

            $etapaAnterior.hide(
                'slide',
                { direction: 'left' },
                function(){

                    $etapaAtual.show(
                        'slide',
                        {direction: 'right'},
                        function(){
                            $barraProgresso.progressbar( {value: (100 / totalEtapas) * numEtapaAtual } );
                            $h3EtapaProgresso.text('Etapa ' + numEtapaAtual + ' de ' + totalEtapas);
                        }
                    );

                }
            );

        } else {

            $etapaAtual.show(
                'slide',
                {direction: 'right'},
                function(){
                    $barraProgresso.progressbar( {value: (100 / totalEtapas) * numEtapaAtual } );
                    $h3EtapaProgresso.text('Etapa ' + numEtapaAtual + ' de ' + totalEtapas);
                }
            );

        }

        if ( etapasQuickstart.length == 0 ) {
            $aPularEtapa.remove();
            $aPularTodasEtapas.remove();
            $aSalvarEtapa.text('Salvar e Finalizar')
        }
    }

    function exibirProximaEtapa() {
        if ( numEtapaAtual < totalEtapas ) {

            $etapaAnterior = $etapaAtual;
            $etapaAtual = etapasQuickstart.shift();
            numEtapaAtual++;

            exibirEtapaAtual();

        } else {

            finalizarQuickstart();

        }
    }

    function finalizarQuickstart() {
        $.get(
            WeLearn.url.siteURL('quickstart/finalizar'),
            {},
            function(res) {
                if (res.success) {
                    window.location = WeLearn.url.siteURL('/home');
                } else {
                    WeLearn.notificar({
                        msg: res.errors[0].error_msg,
                        nivel: 'error',
                        tempo: 5000
                    })
                }
            }
        );

    }

    function init() {
        $aSalvarEtapa.click(function(e){
            e.preventDefault();

            salvarEtapaAtual();
        });

        $aPularEtapa.click(function(e){
            e.preventDefault();

            exibirProximaEtapa();
        });

        $aPularTodasEtapas.click(function(e){
            e.preventDefault();

            finalizarQuickstart();
        });

        exibirProximaEtapa();
    }

    init();

})();
