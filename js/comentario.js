(function(){

    var $sltAulas = $('#slt-aulas'),
        $sltPaginas = $('#slt-paginas'),
        $navSelectLocalComentario = $('#nav-select-local-comentario'),
        $aExibirLocalComentario = $('#a-exibir-select-local-comentario'),
        $aEsconderLocalComentario = $('#a-esconder-select-local-comentario'),
        $h4MsgEscolherPagina = $('#h4-msg-escolher-pagina'),
        $divComentarioListaContainer = $('#div-comentario-lista-container'),
        $emQtdComentarios = $('#em-qtd-comentarios'),
        $emTotalComentarios = $('#em-total-comentarios'),
        $emNomePagina = $('#em-nome-pagina'),
        $emNomeAula = $('#em-nome-aula'),
        $hdrPaginacaoComentarios = $('#hdr-paginacao-comentarios'),
        $h4MsgSemmaisPaginas = $('#h4-msg-sem-mais-paginas'),
        $aPaginacaoComentarios = $('#a-paginacao-comentarios'),
        $h4MsgSemComentarios = $('#h4-msg-sem-comentarios'),
        $ulListaComentario = $('#ul-lista-comentario'),
        $divFormCriarComentarioContainer = $('#div-form-criar-comentario-container'),
        formComentarioCriar = document.getElementById('form-comentario-criar'),
        $hiddenInputPaginaId = $('input[name=paginaId]'),
        $txtAssunto = $('#txt-assunto'),
        $txtComentario = $('#txt-comentario');

    $aExibirLocalComentario.click(function(e){
        e.preventDefault();

        $(this).fadeOut(function(){
            $navSelectLocalComentario.fadeIn();
        });
    });

    $aEsconderLocalComentario.click(function(e){
        e.preventDefault();

        $navSelectLocalComentario.fadeOut(function(){
            $aExibirLocalComentario.fadeIn();
        });
    });

    $('#slt-modulos').change(function(e){
        var $this = $(this);

        if ( $this.val() != '0' ) {
            $.get(
                WeLearn.url.siteURL('/conteudo/aula/recuperar_lista/' + $this.val()),
                {},
                function(res) {
                    if (res.success) {
                        var htmlOptions;

                        if ( res.aulas.length > 0 ) {
                            htmlOptions = '<option value="0">Selecione uma aula...</option>';
                            htmlOptions += WeLearn.helpers.objectListToSelectOptions( res.aulas );

                        } else {
                            htmlOptions = '<option value="0">Não há aulas cadastradas neste módulo...</option>';
                        }

                        $sltAulas.html( htmlOptions );

                        $sltPaginas.parent().hide();
                        $sltAulas.parent().fadeIn();
                    } else {
                        WeLearn.notificar({
                            nivel: 'error',
                            msg: res.errors[0].error_msg,
                            tempo: 5000
                        });
                    }
                }
            );
        } else {
            $sltPaginas.parent().hide()
                       .prev().hide();
        }
    });

    $sltAulas.change(function(e){
        var $this = $(this);

        if ( $this.val() != '0' ) {
            $.get(
                WeLearn.url.siteURL('/conteudo/pagina/recuperar_lista/' + $this.val()),
                {},
                function(res) {
                    if (res.success) {
                        var htmlOptions;

                        if ( res.paginas.length > 0 ) {
                            htmlOptions = '<option value="0">Selecione uma página...</option>';
                            htmlOptions += WeLearn.helpers.objectListToSelectOptions( res.paginas );
                        } else {
                            htmlOptions = '<option value="0">Não há páginas cadastradas nesta aula...</option>';
                        }

                        $sltPaginas.html( htmlOptions );

                        $sltPaginas.parent().fadeIn();
                    } else {
                        WeLearn.notificar({
                            nivel: 'error',
                            msg: res.errors[0].error_msg,
                            tempo: 5000
                        });
                    }
                }
            );
        } else {
            $sltPaginas.parent().hide();
        }
    });

    $sltPaginas.change(function(e){
        var $this = $(this);

        if ( $this.val() == '0' ) { return; }

        $.get(
            WeLearn.url.siteURL('/conteudo/comentario/recuperar_lista/' + $this.val()),
            {},
            function(res) {
                if (res.success) {
                    $h4MsgEscolherPagina.hide();

                    $emQtdComentarios.text( res.qtdComentarios );
                    $emTotalComentarios.text( res.totalComentarios );
                    $emNomePagina.text( res.nomePagina );
                    $emNomeAula.text( res.nomeAula );
                    $ulListaComentario.data('id-pagina', $this.val())
                                      .empty();

                    if (res.qtdComentarios > 0) {
                        $h4MsgSemComentarios.hide();

                        $ulListaComentario.html(res.htmlListaComentarios)
                                          .show();

                        if (res.paginacao.proxima_pagina) {
                            $h4MsgSemmaisPaginas.hide();
                            $aPaginacaoComentarios.data('id-proximo', res.paginacao.inicio_proxima_pagina)
                                                  .data('id-pagina', $this.val())
                                                  .show();
                        } else {
                            $aPaginacaoComentarios.data('id-proximo', '')
                                                  .data('id-pagina', '')
                                                  .hide();
                            $h4MsgSemmaisPaginas.show();
                        }

                        $hdrPaginacaoComentarios.show();
                    } else {
                        $ulListaComentario.hide();
                        $hdrPaginacaoComentarios.hide();

                        $h4MsgSemComentarios.show();
                    }

                    $navSelectLocalComentario.fadeOut(function(){
                        $aEsconderLocalComentario.show();
                        $aExibirLocalComentario.fadeIn();
                        $divComentarioListaContainer.fadeIn();
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
    });

    $aPaginacaoComentarios.click(function(e){
        e.preventDefault();

        var $this = $(this);

        $.get(
            WeLearn.url.siteURL('/conteudo/comentario/recuperar_lista/' + $this.data('id-pagina')),
            { inicioProxPagina: $this.data('id-proximo') },
            function(res) {
                if (res.success) {
                    $emQtdComentarios.text( parseInt($emQtdComentarios.text()) + res.qtdComentarios );

                    $ulListaComentario.prepend(res.htmlListaComentarios);

                    if (res.paginacao.proxima_pagina) {
                        $aPaginacaoComentarios.data('id-proximo', res.paginacao.inicio_proxima_pagina);
                    } else {
                        $aPaginacaoComentarios.data('id-proximo', '')
                                              .hide();
                        $h4MsgSemmaisPaginas.show();
                    }
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

    $('a.a-exibir-form-criar-comentario').click(function(e){
        e.preventDefault();

        $divFormCriarComentarioContainer.slideToggle(function(){
            if( $divFormCriarComentarioContainer.is(':visible') ) {
                $txtAssunto.focus();
            }
        });
    });

    $('#btn-form-comentario-criar').click(function(e){
        e.preventDefault();

        $hiddenInputPaginaId.val( $ulListaComentario.data('id-pagina') );

        WeLearn.validarForm(
            formComentarioCriar,
            $(formComentarioCriar).attr('action'),
            function(res) {
                var $htmlNovoComentario = $(res.htmlNovoComentario);
                $htmlNovoComentario.hide();

                $ulListaComentario.append( $htmlNovoComentario );
                if( $ulListaComentario.children().length <= 1 ) {
                    $h4MsgSemComentarios.fadeOut(function(){
                        $ulListaComentario.fadeIn();
                        $htmlNovoComentario.fadeIn();
                    });
                } else {
                    $htmlNovoComentario.fadeIn();
                }

                $emQtdComentarios.text( parseInt($emQtdComentarios.text()) + 1 );
                $emTotalComentarios.text( parseInt($emTotalComentarios.text()) + 1 );

                $divFormCriarComentarioContainer.slideUp(function(){
                    $txtAssunto.val('');
                    $txtComentario.val('');
                });

                WeLearn.notificar( res.notificacao );
            }
        );
    });

    var $divDialogoFormAlteracao = $(
            '<div/>',
            {id : 'div-dialogo-form-alteracao-comentario'}
        ).dialog({
            autoOpen: false,
            resizable: false,
            width: 610,
            height: 600,
            title: 'Alterar Comentário',
            beforeClose: function(){
                $(this).empty();
            }
        }),
        $liItemComentario;
    $('a.a-alterar-comentario').live('click',function(e){
        e.preventDefault();

        var $this = $(this);

        $liItemComentario = $this.parent()
                                 .parent()
                                 .parent()
                                 .parent()
                                 .parent()
                                 .parent()
                                 .parent();

        $.get(
            $this.attr('href'),
            {},
            function(res) {
                $divDialogoFormAlteracao.html(res.htmlFormAlterar)
                                        .dialog('option', 'buttons', {
                                            'Cancelar': function(){
                                                $(this).dialog('close');
                                            }
                                        })
                                        .dialog('open');
            }
        );
    });

    $('#btn-form-comentario-alterar').live('click', function(e){
        e.preventDefault();

        var formAlterar = document.getElementById('form-comentario-alterar');

        WeLearn.validarForm(
            formAlterar,
            $(formAlterar).attr('action'),
            function(res) {
                $liItemComentario.replaceWith(res.htmlComentarioAlterado);

                WeLearn.notificar(res.notificacao);
                $divDialogoFormAlteracao.dialog('close');
            }
        );
    });

    var $divConfirmacaoRemoverComentario = $('<div id="dialogo-confirmacao-remover-comentario">' +
                                            '<p>Tem certeza que deseja remover este Comentário?<br/>' +
                                            'Esta ação <strong>NÃO</strong> poderá ser desfeita.</p></div>');
    $('a.a-remover-comentario').live('click', function(e){
        e.preventDefault();

        var $this = $(this);

        $divConfirmacaoRemoverComentario.dialog({
            title: 'Tem Certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar': function() {
                    var $dialogThis = $( this );

                    $.get(
                        $this.attr('href'),
                        {},
                        function(res) {
                            if (res.success) {

                                $this.parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .parent()
                                     .fadeOut(function(){
                                         $( this ).remove();
                                     });

                                $dialogThis.dialog('close');

                                $emQtdComentarios.text( parseInt($emQtdComentarios.text()) - 1 );
                                $emTotalComentarios.text( parseInt($emTotalComentarios.text()) - 1 );

                                WeLearn.notificar(res.notificacao);

                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 5000
                                });
                            }
                        },
                        'json'
                    );
                },
                'Cancelar': function() {
                    $( this ).dialog('close');
                }
            }
        });
    });

    $(document).ready(function(){
        if ( WeLearn.url.params.p ) {
            $sltPaginas.trigger('change');
        }
    });

})();