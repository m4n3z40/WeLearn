/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 17/11/11
 * Time: 13:40
 * To change this template use File | Settings | File Templates.
 */
(function(){
    var navPaginacaoForum = document.getElementById('paginacao-forum-lista');
    if (navPaginacaoForum != null) {
        var $aProxPaginaForum = $(navPaginacaoForum).find('a');

        $aProxPaginaForum.click(function(e){
            e.preventDefault();

            var $this = $(this),
                idProximo = $this.data('proximo'),
                idCategoria = $this.data('id-categoria'),
                url = WeLearn.url.siteURL('forum/forum/proxima_pagina/' + idCategoria + '/' + idProximo);

            $.get(
                url,
                (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
                function (res) {
                    if(res.success) {
                        $('#forum-lista-forums').append(res.htmlListaForuns);

                        if (res.paginacao.proxima_pagina) {
                            $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                        } else {
                            $this.parent().html('<h4>Não há mais fóruns a serem exibidos.</h4>')
                        }
                    } else {
                        WeLearn.notificar({
                            msg: res.errors[0].error_msg,
                            nivel: 'erro',
                            tempo: 15000
                        });
                    }
                },
                'json'
            )
        });
    }

    var visualizandoListaForuns = (document.getElementById('forum-lista-forums') != null);

    $('.a-alterarstatus-forum').live('click', function(e) {
        e.preventDefault();

        var $this = $(this),
            $divConfirmacao = $('<div id="dialogo-confirmacao-alterarstatus-forum">' +
                                '<p>Tem certeza que deseja alterar o status deste fórum?<br/>' +
                                '<strong>Somente</strong> fóruns <strong>ATIVOS</strong> podem ser visualizados' +
                                ' pelos alunos.</p></div>');

        $divConfirmacao.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        $this.attr('href'),
                        {},
                        function(res) {
                            if (res.success) {
                                WeLearn.notificar(res.notificacao);

                                if (visualizandoListaForuns) {
                                    $this.parent().parent().parent().parent().fadeOut('slow', function(){
                                        $( this ).remove();
                                    });
                                } else {
                                    if (res.statusAtual == 'ativado') {
                                        $this.text('Desativar este fórum');
                                    } else {
                                        $this.text('Ativar este fórum');
                                    }
                                }
                            } else {
                                WeLearn.notificar({
                                    nivel: 'erro',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
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

    $('.a-remover-forum').live('click', function(e) {
        e.preventDefault();

        var $this = $(this),
            $divConfirmacao = $('<div id="dialogo-confirmacao-remover-forum">' +
                                '<p>Tem certeza que deseja remover este fórum?<br/>' +
                                'Esta ação <strong>NÃO</strong> poderá ser desfeita!<br/>' +
                                '<strong>TODOS</strong> os posts deste fórum serão perdidos!</p></div>');

        $divConfirmacao.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar' : function() {
                    $.get(
                        $this.attr('href'),
                        {},
                        function(res) {
                            if (res.success) {
                                WeLearn.notificar(res.notificacao);
                                if (visualizandoListaForuns) {
                                    $this.parent().parent().parent().parent().fadeOut('slow', function(){
                                        $( this ).remove();
                                    });
                                } else {
                                    window.location = WeLearn.url.siteURL('/curso/forum/listar/' + res.idCategoria)
                                }
                            } else {
                                WeLearn.notificar({
                                    nivel: 'erro',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
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

    var navPaginacaoCategorias = document.getElementById('paginacao-forum-categorias');
    if (navPaginacaoCategorias != null) {
        var $aProxPaginaCategorias = $(navPaginacaoCategorias).find('a');

        $aProxPaginaCategorias.click(function(e) {
            e.preventDefault();

            var $this = $(this),
                idProximo = $this.data('proximo'),
                idCurso = $this.data('id-curso'),
                url = WeLearn.url.siteURL('forum/forum/proxima_pagina_categorias/' + idCurso + '/' + idProximo);

            $.get(
                url,
                {},
                function (res) {
                    if(res.success) {
                        $('#forum-lista-categorias').append(res.htmlListaCategorias);

                        if (res.paginacao.proxima_pagina) {
                            $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                        } else {
                            $this.parent().html('<h4>Não há mais categorias a serem exibidas no momento.</h4>');
                        }
                    } else {
                        WeLearn.notificar({
                            msg: res.errors[0].error_msg,
                            nivel: 'erro',
                            tempo: 15000
                        })
                    }
                },
                'json'
            );
        });
    }

    var btnFormForum = document.getElementById('btn-form-forum');
    if (btnFormForum != null) {
        $(btnFormForum).click(function(e){
            e.preventDefault();

            var formForum = document.getElementById('form-criar-forum'),
                url = WeLearn.url.siteURL('forum/forum/salvar');

            formForum = (formForum != null) ? formForum : document.getElementById('form-alterar-forum');

            if(formForum != null) {
                WeLearn.validarForm(formForum, url, function(res) {
                    window.location = WeLearn.url.siteURL('curso/forum/post/listar/' + res.idForum);
                });
            }
        });
    }
})();