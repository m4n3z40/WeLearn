/**
 * Created with JetBrains PhpStorm.
 * User: allan
 * Date: 28/05/12
 * Time: 12:06
 * To change this template use File | Settings | File Templates.
 */

(function(){

    var $divJanelaAula = $('#div-visualizacao-conteudo-janela-aula').dialog({
            autoOpen: false,
            resizable: false,
            draggable: false,
            modal: true,
            position: 'top',
            show: 'fade',
            hide: 'fade',
            buttons: {
                'Sair' : function() {
                    $divJanelaAula.dialog('close');
                }
            },
            open: function() {
                recuperarComentarios( $wrapperSalaDeAula.data('id-pagina') );
            }
        }),
        $window = $(window),
        $wrapperSalaDeAula = $('#exibicao-conteudo-saladeaula'),
        $sltModulos = $('#slt-modulos'),
        $sltAulas = $('#slt-aulas'),
        $sltPaginas = $('#slt-paginas');

    $('#btn-iniciar-visualizacao-conteudo').click(function(e){
        $divJanelaAula
            .dialog('option', 'height', $window.height() - 6)
            .dialog('option', 'width', $window.width() - 80)
            .dialog('open');
    });

    /* #################################################### */

    $sltModulos.change(function(e){
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

                        $sltPaginas.parent().hide()
                                   .prev().hide();

                        $sltAulas.parent().fadeIn()
                                 .prev().fadeIn();
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
            $sltAulas.parent().hide()
                     .prev().hide();

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

                        $sltPaginas.parent().fadeIn()
                                   .prev().fadeIn();
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

    $sltPaginas.change(function(e){
        var $this = $(this);

        if ( $this.val() == '0' ) { return; }
    });

    /*####################################*/

    var $preAnotacao = $('#div-anotacao-form-container').find('pre'),
        $txtAnotacao = $('#txt-anotacao'),
        formAnotacao = document.getElementById( 'exibicao-conteudo-anotacao-form' );

    $preAnotacao.click(function(){
        $preAnotacao.hide();
        $txtAnotacao.show().focus();
    });

    $txtAnotacao.blur(function(){
        var anotacao = $txtAnotacao.val();

        if( anotacao && anotacao != $preAnotacao.text() ) {

            WeLearn.validarForm(
                formAnotacao,
                $(formAnotacao).attr('action'),
                function(res){
                    $preAnotacao.text( anotacao );
                    $txtAnotacao.hide();
                    $preAnotacao.show();
                }
            );

        } else {
            $txtAnotacao.hide();
            $preAnotacao.show();
        }
    });

    /* #################################################### */

    var $divComentarioListaContainer = $('#div-comentario-lista-container'),
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
        $txtComentario = $('#txt-comentario'),
        recuperarComentarios = function(idPagina) {

            $.get(
                WeLearn.url.siteURL('/conteudo/comentario/recuperar_lista/' + idPagina),
                {},
                function(res) {
                    if (res.success) {

                        $emQtdComentarios.text( res.qtdComentarios );
                        $emTotalComentarios.text( res.totalComentarios );
                        $emNomePagina.text( res.nomePagina );
                        $emNomeAula.text( res.nomeAula );
                        $ulListaComentario.data('id-pagina', idPagina)
                                          .empty();

                        if (res.qtdComentarios > 0) {
                            $h4MsgSemComentarios.hide();

                            $ulListaComentario.html(res.htmlListaComentarios)
                                              .show();

                            if (res.paginacao.proxima_pagina) {
                                $h4MsgSemmaisPaginas.hide();
                                $aPaginacaoComentarios.data('id-proximo', res.paginacao.inicio_proxima_pagina)
                                                      .data('id-pagina', idPagina)
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

                        $divComentarioListaContainer.fadeIn();

                    } else {
                        WeLearn.notificar({
                            nivel: 'error',
                            msg: res.errors[0].error_msg,
                            tempo: 5000
                        });
                    }
                }
            );

        };

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

    /* ############################################################ */

    var $aExibirRecursosGerais = $('#a-exibir-recursos-gerais'),
        $aExibirRecursosRestritos = $('#a-exibir-recursos-restritos'),
        $divRecursosGerais = $('#div-recursos-gerais'),
        $emQtdRecursosGerais = $('#em-qtd-recursos-gerais'),
        $emTotalRecursosGerais = $('#em-total-recursos-gerais'),
        $h4SemRecursosGerais = $('#h4-msg-sem-recursos-gerais'),
        $ulListaRecursosGerais = $('#ul-lista-recursos-gerais'),
        $fooPaginacaoRecursosGerais = $('#foo-paginacao-recursos-gerais'),
        $h4SemMaisRecursosGerais = $('#h4-msg-sem-mais-recursos-gerais'),
        $aPaginacaoRecursosGerais = $('#a-paginacao-recursos-gerais'),        
        $divRecursosRestritos = $('#div-recursos-restritos'),
        $emQtdRecursosRestritos = $('#em-qtd-recursos-restritos'),
        $emTotalRecursosRestritos = $('#em-total-recursos-restritos'),
        $emNomeAulaRecursosRestritos = $('#em-nome-aula-recursos-restritos'),
        $h4SemRecursosRestritos = $('#h4-msg-sem-recursos-restritos'),
        $ulListaRecursosRestritos = $('#ul-lista-recursos-restritos'),
        $fooPaginacaoRecursosRestritos = $('#foo-paginacao-recursos-restritos'),
        $h4SemMaisRecursosRestritos = $('#h4-msg-sem-mais-recursos-restritos'),
        $aPaginacaoRecursosRestritos = $('#a-paginacao-recursos-restritos'),
        requestListaRecursos = function(tipoRecurso, idParent, idProximo) {

            var url = WeLearn.url.siteURL('conteudo/recurso/recuperar_recursos_aluno/'
                                          + tipoRecurso + '/'
                                          + idParent);

            if ( idProximo ) {
                url = url + '/' + idProximo;
            }

            return $.ajax({
                url: url,
                dataType: 'json',
                success: function(res) {

                    if ( ! res.success ) {

                        WeLearn.notificar({
                            nivel: 'error',
                            msg: res.errors[0].error_msg,
                            tempo: 5000
                        });

                    }

                }
            });
        };
        
    $aExibirRecursosGerais.click(function(e){
        e.preventDefault();

        if ( $divRecursosGerais.is(':visible') ) { return; }

        $divRecursosRestritos.hide();

        var $this = $(this),
            tipoRecurso = $this.data('tipo-recurso'),
            idCurso = $wrapperSalaDeAula.data('id-curso');

        requestListaRecursos(tipoRecurso, idCurso).done(function(res){

            if ( res.success ) {

                if ( res.totalRecursos > 0 ) {

                    $emQtdRecursosGerais.text( res.qtdRecuperados );
                    $emTotalRecursosGerais.text( res.totalRecursos );
                    $ulListaRecursosGerais.html( res.htmlListaRecursos).show();

                    if ( res.paginacao.proxima_pagina ) {

                        $h4SemMaisRecursosGerais.hide();

                        $aPaginacaoRecursosGerais.data(
                            'proximo',
                            res.paginacao.inicio_proxima_pagina
                        ).show();

                    } else {

                        $aPaginacaoRecursosGerais.data('proximo', '').hide();

                        $h4SemMaisRecursosGerais.show();

                    }

                    $fooPaginacaoRecursosGerais.show();

                } else {

                    $ulListaRecursosGerais.hide();
                    $fooPaginacaoRecursosGerais.hide();

                    $h4SemRecursosGerais.show();

                }

                $divRecursosGerais.slideDown();

            }

        });

    });

    $aPaginacaoRecursosGerais.click(function(e){
        e.preventDefault();

        var $this = $(this),
            tipoRecurso = $this.data('tipo-recurso'),
            idCurso = $wrapperSalaDeAula.data('id-curso'),
            proximo = $this.data('proximo');

        requestListaRecursos(tipoRecurso, idCurso, proximo).done(function(res){

            if ( res.success ) {

                $emQtdRecursosGerais.text(
                    parseInt( $emQtdRecursosGerais.text() ) + res.qtdRecuperados
                );
                $ulListaRecursosGerais.append( res.htmlListaRecursos);

                if ( res.paginacao.proxima_pagina ) {

                    $aPaginacaoRecursosGerais.data(
                        'proximo',
                        res.paginacao.inicio_proxima_pagina
                    );

                } else {

                    $aPaginacaoRecursosGerais.data('proximo', '').hide();

                    $h4SemMaisRecursosGerais.show();

                }

            }

        });
    });

    $aExibirRecursosRestritos.click(function(e){
        e.preventDefault();

        if ( $divRecursosRestritos.is(':visible') ) { return; }

        $divRecursosGerais.hide();

        var $this = $(this),
            tipoRecurso = $this.data('tipo-recurso'),
            idAula = $wrapperSalaDeAula.data('id-aula');

        requestListaRecursos(tipoRecurso, idAula).done(function(res){

            if ( res.success ) {

                if ( res.totalRecursos > 0 ) {

                    $emQtdRecursosRestritos.text( res.qtdRecuperados );
                    $emTotalRecursosRestritos.text( res.totalRecursos );
                    $emNomeAulaRecursosRestritos.text( res.nomeAula );
                    $ulListaRecursosRestritos.html( res.htmlListaRecursos).show();

                    if ( res.paginacao.proxima_pagina ) {

                        $h4SemMaisRecursosRestritos.hide();

                        $aPaginacaoRecursosRestritos.data(
                            'proximo',
                            res.paginacao.inicio_proxima_pagina
                        ).show();

                    } else {

                        $aPaginacaoRecursosRestritos.data('proximo', '').hide();

                        $h4SemMaisRecursosRestritos.show();

                    }

                    $fooPaginacaoRecursosRestritos.show();

                } else {

                    $ulListaRecursosRestritos.hide();
                    $fooPaginacaoRecursosRestritos.hide();

                    $h4SemRecursosRestritos.show();

                }

                $divRecursosRestritos.slideDown();

            }

        });

    });

    $aPaginacaoRecursosRestritos.click(function(e){
        e.preventDefault();

        var $this = $(this),
            tipoRecurso = $this.data('tipo-recurso'),
            idAula = $wrapperSalaDeAula.data('id-aula'),
            proximo = $this.data('proximo');

        requestListaRecursos(tipoRecurso, idAula, proximo).done(function(res){

            if ( res.success ) {

                $emQtdRecursosRestritos.text(
                    parseInt( $emQtdRecursosRestritos.text() ) + res.qtdRecuperados
                );
                $ulListaRecursosRestritos.append( res.htmlListaRecursos);

                if ( res.paginacao.proxima_pagina ) {

                    $aPaginacaoRecursosRestritos.data(
                        'proximo',
                        res.paginacao.inicio_proxima_pagina
                    );

                } else {

                    $aPaginacaoRecursosRestritos.data('proximo', '').hide();

                    $h4SemMaisRecursosRestritos.show();

                }

            }

        });

    });

    /* ############################################################ */

    $('#a-nav-exibicao-inicio-aula').click(function(e){
        e.preventDefault();

    });

    $('#a-nav-exibicao-pagina-anterior').click(function(e){
        e.preventDefault();

    });

    $('#a-nav-exibicao-proxima-pagina').click(function(e){
        e.preventDefault();

    });

})();