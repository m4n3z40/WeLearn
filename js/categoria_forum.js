/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 19/10/11
 * Time: 02:15
 * To change this template use File | Settings | File Templates.
 */
(function(){
    var btnFormCategoriaForum = document.getElementById('btn-form-categoria-forum');
    if (btnFormCategoriaForum != null) {
        $(btnFormCategoriaForum).click(function(e){
            e.preventDefault();

            var formCategoria = document.getElementById('form-criar-categoria-forum'),
                url = WeLearn.url.siteURL('forum/categoria/salvar');

            formCategoria = (formCategoria != null) ? formCategoria : document.getElementById('form-alterar-categoria-forum');

            if (formCategoria != null) {
                WeLearn.validarForm(formCategoria, url, function(res) {
                   window.location = WeLearn.url.siteURL('curso/forum/categoria/listar/' + res.idCurso);
                });
            }
        });
    }

    $('.a-remover-categoria-forum').live('click', function (e) {
        e.preventDefault();

        var $this = $(this),
            $divConfirmacao = $('<div id="dialogo-confirmacao-remover-categoria-forum">' +
                                '<p>Tem certeza que deseja remover esta categoria?' +
                                '<br/>Essa ação <strong>NÃO</strong> poderá ser desfeita. ' +
                                '<br/><strong>TODOS</strong> os fóruns e posts vinculados' +
                                ' à esta categoria também serão removidos.</p></div>');

        $divConfirmacao.dialog({
            title: 'Tem certeza?',
            width: '450px',
            resizable: false,
            modal: true,
            buttons: {
                'Confirmar': function(){
                    $.get(
                        $this.attr('href'),
                        {},
                        function(res) {
                            if (res.success) {
                                WeLearn.notificar(res.notificacao);
                                $this.parent().parent().parent().parent().parent().fadeOut('slow', function(){
                                    $(this).remove();
                                });
                            } else {
                                WeLearn.notificar({
                                    nivel: 'error',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
                                });
                            }
                        }
                    );

                    $( this ).dialog('close');
                },
                'Cancelar': function(){
                    $( this ).dialog('close');
                }
            }
        });
    });

    var navPaginacao = document.getElementById('paginacao-categoria-forum');
    if (navPaginacao != null) {
        var $aProxPagina = $(navPaginacao).find('a');

        $aProxPagina.click(function(e) {
            e.preventDefault();

            var $this = $(this),
                idProximo = $this.data('proximo'),
                idCurso = $this.data('id-curso'),
                url = WeLearn.url.siteURL('forum/categoria/proxima_pagina/' + idCurso + '/' + idProximo);

            $.get(
                url,
                {},
                function (res) {
                    if(res.success) {
                        $('#categoria-forum-listar-datatable').append(res.htmlListaCategorias);

                        if (res.paginacao.proxima_pagina) {
                            $this.data('proximo', res.paginacao.inicio_proxima_pagina);
                        } else {
                            $this.parent().html('<h4>Não há mais categorias a serem exibidas no momento.</h4>');
                        }
                    } else {
                        WeLearn.notificar({
                            msg: res.errors[0].error_msg,
                            nivel: 'error',
                            tempo: 10000
                        })
                    }
                },
                'json'
            );
        });
    }
})();