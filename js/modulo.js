(function(){

    var formModulo = document.getElementById('modulo-criar-form') ||
                     document.getElementById('modulo-alterar-form');

    $('#btn-form-modulo').click(function(e){
        e.preventDefault();

        WeLearn.validarForm(
            formModulo,
            $(formModulo).attr('action'),
            function (res) {
                window.location = WeLearn.url.siteURL('/curso/conteudo/modulo/'
                                                      + res.idCurso);
            }
        )
    });

    var atualizarNroOrdem = function($ulListaModulo) {
            $ulListaModulo
                .children('li')
                .find('> h3 > a > em')
                .each(function(index, el){
                   $(el).text(index + 1);
                });
        },
        ulListaModuloToParam = function($ulListaModulo) {
            return WeLearn.helpers.accordionToURLParamPosicoes($ulListaModulo);
        },
        $divGerenciarPosicoesModulo = $('.div-modulo-gerenciar-posicoes'),
        $btnSalvarPosicoesModulo = $divGerenciarPosicoesModulo.children('button'),
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
                if( ! $divGerenciarPosicoesModulo.first().is(':visible') ) {
                    $divGerenciarPosicoesModulo.show();
                }

                atualizarNroOrdem( $(this) );
            }
        },
        $ulListaModulo = $('#ul-modulo-listar-lista')
                            .accordion(accordionOptions)
                            .sortable(sortableOptions)
                            .disableSelection();

    $btnSalvarPosicoesModulo.click(function(e){
        e.preventDefault();

        $ulListaModulo.sortable('refresh');

        $btnSalvarPosicoesModulo
            .addClass('disabled')
            .attr('disabled', 'disabled');

        var urlSalvarPosicoes = WeLearn.url.siteURL(
                '/conteudo/modulo/salvar_posicoes/' + $ulListaModulo.data('id-curso')
            ),
            parametrosGet = ulListaModuloToParam($ulListaModulo);

        $.get(
            urlSalvarPosicoes,
            parametrosGet,
            function(res) {
                if (res.success) {
                    $btnSalvarPosicoesModulo
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

    var $divConfirmacaoRemover = $('<div id="dialogo-confirmacao-remover-modulo">' +
                                   '<p>Tem certeza que deseja remover este módulo?<br/>' +
                                   'Esta ação <strong>NÃO</strong> poderá ser desfeita!<br/>' +
                                   '<strong>TODAS</strong> as aulas vinculadas a este ' +
                                   'módulo serão <strong>PERDIDAS!</strong></p></div>'),
        $qtdTotalModulos = $('#p-modulo-listar-qtdTotal > strong').first();
    $('.a-modulo-remover').live('click', function(e){
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

                    $ulListaModulo.sortable('refresh');

                    $.get(
                        url,
                        ulListaModuloToParam($ulListaModulo),
                        function(res) {
                            if (res.success) {
                                WeLearn.notificar(res.notificacao);
                            } else {
                                window.location.reload();
                            }
                        }
                    );

                    $( this ).dialog('close');

                    atualizarNroOrdem($ulListaModulo);
                    $ulListaModulo.accordion('destroy');
                    $ulListaModulo.accordion(accordionOptions);

                    $qtdTotalModulos.text( parseInt( $qtdTotalModulos.text() ) - 1 );
                },
                'Cancelar' : function() {
                    $( this ).dialog('close');
                }
            }
        });
    });

})();
