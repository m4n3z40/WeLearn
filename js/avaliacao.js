(function(){

    var formAvaliacao = document.getElementById('form-avaliacao-criar')
                     || document.getElementById('form-avaliacao-alterar');

    $('button#btn-form-avaliacao').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formAvaliacao,
            $(formAvaliacao).attr('action'),
            function(res) {
                window.location = WeLearn.url.siteURL('/curso/conteudo/avaliacao/exibir/' + res.idAvaliacao);
            }
        )
    });

    var $divConfirmacaoRemoverAvaliacao = $('<div id="dialogo-confirmacao-remover-avaliacao">' +
                                           '<p>Tem certeza que deseja remover esta avaliação?<br/>' +
                                           'Esta ação <strong>NÃO</strong> poderá ser desfeita!<br/>' +
                                           '<strong>TODAS</strong> as informações vinculadas a esta ' +
                                           'avaliação também serão <strong>PERDIDAS!</strong> ' +
                                           '(Questões, Alternativas, Respostas de Alunos e etc...)</p></div>');
    $('a.a-remover-avaliacao').click(function(e){
        e.preventDefault();

        var $this = $(this),
            url = $this.attr('href');

        $divConfirmacaoRemoverAvaliacao.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        url,
                        {},
                        function(res) {
                            if (res.success) {
                                window.location = WeLearn.url.siteURL('/curso/conteudo/avaliacao/' + res.idCurso);
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 5000
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

    $('#btn-alterar-qtdquestoesexibir').click(function(e){
        var $this = $(this),
            $qtdQuestoesExibir = $this.prev(),
            qtdQuestoesExibir = $qtdQuestoesExibir.text(),
            $parent = $this.parent();

        switch( $this.data('acao') ) {
            case 'alterar':
                $parent.prepend($(
                    '<input/>',
                    {
                        type: 'number',
                        value: qtdQuestoesExibir,
                        min: 0,
                        max: $qtdQuestoes.first().text()
                    }
                )).append($(
                    '<button/>',
                    {
                        text: 'Cancelar',
                        click: function() {
                            $parent.children().first().remove();
                            $parent.prepend($('<span/>', {text: qtdQuestoesExibir}));
                            $this.text('Alterar').data('acao', 'alterar');
                            $(this).remove();
                        }
                    }
                ));
                $qtdQuestoesExibir.remove();
                $this.text('Salvar').data('acao', 'salvar');
                $parent.first().focus();
                break;
            case 'salvar':
                var avaliacaoId = $('#hdn-id-avaliacao').val(),
                    qtd = $qtdQuestoesExibir.val();
                $.get(
                    WeLearn.url.siteURL('/conteudo/avaliacao/salvar_qtd_questoes_exibir/' + avaliacaoId),
                    { 'qtd':  qtd },
                    function (res) {
                        if (res.success) {
                            WeLearn.notificar( res.notificacao );

                            var $siblings = $parent.children();
                            $siblings.first().remove();
                            $siblings.last().remove();
                            $parent.prepend($('<span/>', {text: qtd}));
                            $this.text('Alterar').data('acao', 'alterar');
                        } else {
                            WeLearn.notificar({
                                nivel: 'error',
                                msg: res.errors[0].error_msg,
                                tempo: 5000
                            });
                        }
                    }
                );
                break;
            default:
                return;
        }
    });

    var $divDialogoFormQuestao = $(
        '<div/>',
        {id : 'div-dialogo-form-questao'}
    ).dialog({
        autoOpen: false,
        resizable: false,
        width: 610,
        height: 600,
        beforeClose: function(){
            $(this).empty();
        }
    });

    $('a.a-adicionar-questao').click(function(e){
        e.preventDefault();

        $.get(
            $(this).attr('href'),
            {},
            function(res) {
                if (res.success) {
                    $divDialogoFormQuestao
                        .html(res.htmlFormCriarQuestao)
                        .dialog('option', 'title', 'Adicionar Questão')
                        .dialog('option', 'buttons', {
                            'Adicionar Questão!' : function(){
                                var formCriar = document.getElementById('form-questao-criar'),
                                    $this = $(this);

                                WeLearn.validarForm(
                                    formCriar,
                                    $(formCriar).attr('action'),
                                    function(res) {
                                        $this.dialog('close');
                                        var $htmlNovaQuestao = $(res.htmlNovaQuestao).hide();

                                        if ($tblListaQuestoes.length == 0) {
                                            var $h4SemLista = $('#h4-questao-listar-semquestao'),
                                                $divContainer = $h4SemLista.parent();

                                            $h4SemLista.remove();
                                            $tblListaQuestoes = $(templateListaQuestoes);
                                            $divContainer.find('nav').last().before($tblListaQuestoes);
                                            $tblListaQuestoes.append($htmlNovaQuestao);
                                        } else {
                                            $tblListaQuestoes.append($htmlNovaQuestao);
                                        }
                                        $htmlNovaQuestao.fadeIn();

                                        $qtdQuestoes.text( parseInt($qtdQuestoes.first().text()) + 1 );

                                        WeLearn.notificar(res.notificacao);
                                    }
                                );
                            },
                            'Cancelar' : function() {
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

    $('a.a-exibir-questao').live('click', function(e){
        e.preventDefault();

        $.get(
            $(this).attr('href'),
            {},
            function(res) {
                if (res.success) {
                    $divDialogoFormQuestao
                        .html(res.htmlExibirQuestao)
                        .dialog('option', 'title', 'Exibir Questão')
                        .dialog('option', 'buttons', {
                            'Fechar' : function() {
                                $(this).dialog('close');
                            }
                        })

                    WeLearn.helpers.initAllSelectablesRadios();

                    $divDialogoFormQuestao.dialog('open');
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

    $('a.a-alterar-questao').live('click', function(e){
        e.preventDefault();

        var $tdEnunciado = $(this).parent().prev().prev();

        $.get(
            $(this).attr('href'),
            {},
            function(res) {
                if (res.success) {
                    $divDialogoFormQuestao
                        .html(res.htmlFormAlterarQuestao)
                        .dialog('option', 'title', 'Alterar Questão')
                        .dialog('option', 'buttons', {
                            'Salvar Questão!' : function(){
                                var formAlterar = document.getElementById('form-questao-alterar'),
                                    $this = $(this);

                                WeLearn.validarForm(
                                    formAlterar,
                                    $(formAlterar).attr('action'),
                                    function(res) {
                                        $this.dialog('close');

                                        WeLearn.notificar( res.notificacao );

                                        $tdEnunciado.text( res.novoEnunciado );
                                    }
                                );
                            },
                            'Cancelar' : function() {
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

    var $divConfirmacaoRemoverQuestao = $('<div id="dialogo-confirmacao-remover-questao">' +
                                           '<p>Tem certeza que deseja remover esta questão?<br/>' +
                                           'Esta ação <strong>NÃO</strong> poderá ser desfeita!<br/>' +
                                           '<strong>TODAS</strong> as informações vinculadas a esta ' +
                                           'questão também serão <strong>PERDIDAS!</strong> ' +
                                           '(Alternativas, Respostas de Alunos e etc...)</p></div>');
    $('a.a-remover-questao').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            url = $this.attr('href');

        $divConfirmacaoRemoverQuestao.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        url,
                        {},
                        function(res) {
                            if (res.success) {
                                WeLearn.notificar(res.notificacao);

                                $this.parent().parent().fadeOut(function(){
                                    $(this).remove();

                                    $qtdQuestoes.text( parseInt($qtdQuestoes.first().text()) - 1 );
                                });
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 5000
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

    var $qtdQuestoes = $('.avaliacao-qtd-questoes'),
        $tblListaQuestoes = $('#tbl-questao-listar-datatable'),
        templateListaQuestoes = '<table id="tbl-questao-listar-datatable"><tr><th>Enunciado</th><th></th><th></th><th></th></tr></table>',
        templateAlternativaIncorreta = function (n) {
        return '<dt><label for="txt-alternativa-incorreta-' + n + '">Alternativa Incorreta ' + n + '</label></dt>' +
               '<dd><textarea name="alternativaIncorreta[]" id="txt-alternativa-incorreta-' + n + '" cols="60" rows="4"></textarea></dd>';
    },
        minAlternativas = 2,
        maxAlternativas = 12;

    $('input#nbr-qtdAlternativasExibir').live('change', function(e){
        $(this).data('alterado', 1);
    });

    $('a#a-adicionar-alternativa').live('click', function(e){
        e.preventDefault();

        var $dlAlternativasIncorretas = $('#dl-questao-alternativasincorretas'),
            n = $dlAlternativasIncorretas.children('dd').length + 1,
            $nbrQtdexibir = $('input#nbr-qtdAlternativasExibir');

        if (n < maxAlternativas) {
            $dlAlternativasIncorretas.append( templateAlternativaIncorreta(n) );

            if ( $nbrQtdexibir.data('alterado') != 1
               && ( parseInt( $nbrQtdexibir.val() ) == n ) ) {
                $nbrQtdexibir.val(n + 1);
            }
        }
    });

    $('a#a-remover-alternativa').live('click', function(e){
        e.preventDefault();

        var $dlAlternativasIncorretas = $('#dl-questao-alternativasincorretas'),
            $dtsAlternativasIncorretas = $dlAlternativasIncorretas.children('dt'),
            n = $dtsAlternativasIncorretas.length + 1,
            $dtRemover = $dtsAlternativasIncorretas.last(),
            $ddRemover = $dtRemover.next(),
            $nbrQtdexibir = $('input#nbr-qtdAlternativasExibir');

        if (n > minAlternativas) {
            $dtRemover.remove();
            $ddRemover.remove();

            if ( $nbrQtdexibir.data('alterado') != 1
               && ( parseInt( $nbrQtdexibir.val() ) == n ) ) {
                $nbrQtdexibir.val(n - 1);
            }
        }
    });

})();