(function(){
    var $formBuscaUsuarios = $('#form-gerenciador-buscar-usuarios'),
        $ulResultadoBusca = $('#ul-lista-usuarios-resultado-busca'),
        $aPaginacaoConvidar = $('#a-paginacao-usuarios-convidar'),
        $hiddenInicioProxPagBusca = $formBuscaUsuarios.find('input[type=hidden][name=inicio]'),
        $ulUsuariosConvidar = $('#ul-lista-usuarios-convidar'),
        $aConfirmarConvites = $('#a-confirmar-usuarios-convidar'),
        $ulListaConvites = $('#ul-lista-convites'),
        $aPaginacaoConvites = $('#a-paginacao-convites'),
        $emQtdConvites = $('.em-qtd-convites'),
        $emTotalConvites = $('.em-total-convites');

    $('#txt-termo').keyup(function(){
        $hiddenInicioProxPagBusca.val( 0 );

        if ( $(this).val() == '' ) {
            return;
        }

        $.post(
            $formBuscaUsuarios.attr('action'),
            $formBuscaUsuarios.serialize(),
            function(res) {
                if ( res.success ) {

                    if (res.qtdResultados > 0) {

                        $ulResultadoBusca.html( res.htmlResultados );

                        if ( res.paginacao.proxima_pagina ) {
                            $aPaginacaoConvidar.data( 'proximo', res.paginacao.inicio_proxima_pagina )
                                               .show();
                        } else {
                            $aPaginacaoConvidar.data('proximo', 0).hide();
                        }

                    } else {

                        $ulResultadoBusca.html('<li>Não há resultados para exibir.</li>');

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

    $aPaginacaoConvidar.click(function(e){
        e.preventDefault();

        $hiddenInicioProxPagBusca.val( $aPaginacaoConvidar.data('proximo') );

        $.post(
            $formBuscaUsuarios.attr('action'),
            $formBuscaUsuarios.serialize(),
            function(res) {
                if ( res.success ) {

                    if (res.qtdResultados > 0) {

                        $ulResultadoBusca.append( res.htmlResultados );

                        if ( res.paginacao.proxima_pagina ) {
                            $aPaginacaoConvidar.data( 'proximo', res.paginacao.inicio_proxima_pagina )
                                               .show();
                        } else {
                            $aPaginacaoConvidar.data('proximo', 0).hide();
                        }

                    } else {

                        $aPaginacaoConvidar.data('proximo', 0).hide();

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

    var adicionarConvie = function($el) {
            var listaConviteStr = $ulUsuariosConvidar.data('lista-convite'),
                listaConviteArr = [],
                novoConvite = $el.data('id-usuario');

            if ( ! listaConviteStr ) {
                $ulUsuariosConvidar.empty();
            } else {
                listaConviteArr = listaConviteStr.split(',');
            }

            if ( novoConvite && listaConviteArr.indexOf( novoConvite ) == -1 ) {
                listaConviteArr.push( $el.data('id-usuario') );

                $ulUsuariosConvidar.data( 'lista-convite', listaConviteArr.join(',') );

                if ( ! $aConfirmarConvites.is(':visible') ) {
                    $aConfirmarConvites.show();
                }

                return true;
            }

            return false;
        },
        removerConvite = function($el) {
            var listaConviteStr = $ulUsuariosConvidar.data('lista-convite'),
                listaConviteArr = listaConviteStr.split(','),
                conviteRemover = $el.data('id-usuario'),
                indexRemover = listaConviteArr.indexOf( conviteRemover );

            if ( indexRemover == -1 ) { return false; }

            listaConviteArr.splice( indexRemover, 1 );

            if ( listaConviteArr.length < 1 ) {
                $ulUsuariosConvidar.append('<li>Não há convites, por enquanto.</li>');

                $aConfirmarConvites.hide();
            }

            $ulUsuariosConvidar.data( 'lista-convite', listaConviteArr.join(',') );

            return true;
        };

    $('.a-adicionar-convite-usuario').live('click', function(e) {
        e.preventDefault();

        var $this = $(this),
            $liEscolhido = $this.parent().parent().parent();

        $liEscolhido.fadeOut(function(){

            if ( adicionarConvie( $liEscolhido ) ) {

                $this.removeClass()
                     .text('Remover')
                     .unbind()
                     .bind('click', function(e){

                        e.preventDefault();

                        removerConvite( $liEscolhido );

                        $liEscolhido.fadeOut(function(){
                            $liEscolhido.remove();
                        });

                    });

                $ulUsuariosConvidar.append( $liEscolhido );

                $liEscolhido.fadeIn();

            } else {

                $liEscolhido.remove();

            }

        });
    });

    $aConfirmarConvites.click(function(e){
        e.preventDefault();

        var idCurso = $ulUsuariosConvidar.data('id-curso'),
            usuarios = $ulUsuariosConvidar.data('lista-convite');

        $.get(
            WeLearn.url.siteURL('/curso/gerenciador/enviar_convites/' + idCurso),
            { 'usuarios' : usuarios },
            function(res) {
                if ( res.success ) {

                    window.location = WeLearn.url.siteURL( '/curso/gerenciador/convites/' + idCurso );

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

    $aPaginacaoConvites.click(function(e){
        e.preventDefault();

        var $this = $(this);

        $.get(
            WeLearn.url.siteURL('/curso/gerenciador/mais_convites/' + $this.data('id-curso')),
            { 'proximo' : $this.data('proximo') },
            function(res) {
                if(res.success) {

                    $ulListaConvites.append( res.htmlConvites );

                    $emQtdConvites.text( parseInt( $emQtdConvites.text() ) + res.qtdConvites );

                    if ( res.paginacao.proxima_pagina ) {
                        $this.data( 'proximo', res.paginacao.inicio_proxima_pagina );
                    } else {
                        $this.replaceWith( '<h4>Não há mais convites a serem exibidos.</h4>' );
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

    $('a.a-cancelar-convite').live('click', function(e){
        e.preventDefault();

        var $this = $(this),
            $liRemover = $this.parent().parent().parent();

        $.get(
            $this.attr('href'),
            { 'usuarioId' : $liRemover.data('id-usuario') },
            function(res) {
                if (res.success) {

                    $liRemover.fadeOut(function(){
                        $liRemover.remove();
                    });

                    $emQtdConvites.text( parseInt( $emQtdConvites.text() ) - 1 );
                    $emTotalConvites.text( parseInt( $emTotalConvites.text() ) - 1 );

                    WeLearn.notificar( res.notificacao );

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

})();