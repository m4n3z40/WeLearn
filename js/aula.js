(function(){

    $('#slt-aula-modulos').change(function(e){
        var idModulo = $(this).val();

        if (idModulo != '0') {
            window.location = WeLearn.url.siteURL('/curso/conteudo/aula/listar/' + idModulo);
        }
    });

    $('#btn-form-aula').click(function(e){
        e.preventDefault();

        var form = document.getElementById('aula-criar-form') ||
                   document.getElementById('aula-alterar-form');

        log($(form).attr('action'));

        WeLearn.validarForm(
            form,
            $(form).attr('action'),
            function(res) {
                window.location = WeLearn.url.siteURL('/curso/conteudo/aula/listar/'
                                                    + res.idModulo);
            }
        )
    });

    var $divAulaAlterarModulo = $('#div-aula-alterar-modulo');
    $('#a-aula-alterar-modulo').click(function(e){
        e.preventDefault();

        $divAulaAlterarModulo.slideToggle();
    });

    var atualizarNroOrdem = function($ulListaAula) {
            $ulListaAula
                .children('li')
                .find('> h3 > a > em')
                .each(function(index, el){
                   $(el).text(index + 1);
                });
        },
        ulListaAulaToParam = function($ulListaAula) {
            return WeLearn.helpers.accordionToURLParamPosicoes($ulListaAula);
        },
        $divGerenciarPosicoesAula = $('.div-aula-gerenciar-posicoes'),
        $btnSalvarPosicoesAula = $divGerenciarPosicoesAula.children('button'),
        accordionOptions = {
            header: '> li > h3',
            collapsible: true,
            active: false,
            autoHeight: false
        },
        sortableOptions = {
            placeholder: 'ui-state-highlight sortable-placeholder',
            axis: 'y',
            handle: 'h3',
            update: function(e) {
                if( ! $divGerenciarPosicoesAula.first().is(':visible') ) {
                    $divGerenciarPosicoesAula.show();
                }

                atualizarNroOrdem( $(this) );
            }
        },
        $ulListaAula = $('#ul-aula-listar-lista')
                            .accordion(accordionOptions)
                            .sortable(sortableOptions)
                            .disableSelection();

    $btnSalvarPosicoesAula.click(function(e) {
        e.preventDefault();

        $ulListaAula.sortable('refresh');

        $btnSalvarPosicoesAula
            .addClass('disabled')
            .attr('disabled', 'disabled');

        var urlSalvarPosicoes = WeLearn.url.siteURL(
                '/conteudo/aula/salvar_posicoes/' + $ulListaAula.data('id-modulo')
            ),
            parametrosGet = ulListaAulaToParam($ulListaAula);

        $.get(
            urlSalvarPosicoes,
            parametrosGet,
            function(res) {
                if (res.success) {
                    $btnSalvarPosicoesAula
                        .removeClass('disabled')
                        .removeAttr('disabled')
                    .parent().hide();

                    WeLearn.notificar(res.notificacao);
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

    var $divConfirmacaoRemover = $('<div id="dialogo-confirmacao-remover-aula">' +
                                   '<p>Tem certeza que deseja remover esta aula?<br/>' +
                                   'Esta ação <strong>NÃO</strong> poderá ser desfeita!<br/>' +
                                   '<strong>TODAS</strong> as páginas vinculadas a esta ' +
                                   'aula também serão <strong>PERDIDAS!</strong></p></div>');
    $('.a-aula-remover').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            url = $this.attr('href');

        $divConfirmacaoRemover.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $this.parent()
                         .parent()
                         .parent()
                         .parent()
                         .parent()
                         .parent()
                         .remove();

                    $ulListaAula.sortable('refresh');

                    $.get(
                        url,
                        ulListaAulaToParam($ulListaAula),
                        function(res) {
                            if (res.success) {
                                WeLearn.notificar(res.notificacao);
                            } else {
                                log(res);
                            }
                        }
                    );

                    $( this ).dialog('close');

                    atualizarNroOrdem($ulListaAula);
                    $ulListaAula.accordion('destroy');
                    $ulListaAula.accordion(accordionOptions);
                },
                'Cancelar' : function() {
                    $( this ).dialog('close');
                }
            }
        });
    });
})();