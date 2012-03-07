/**
 * Created by JetBrains PhpStorm.
 * User: allan
 * Date: 06/12/11
 * Time: 20:00
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(e){
    var formCriarContainer = document.getElementById('form-criar-post-container');
    if (formCriarContainer != null) {
        $('.a-forum-post-criar').click(function(e){
            e.preventDefault();

            $(formCriarContainer).show('slide', {direction: 'up'}, function(){
                $(this).find('input[type=text]').first().focus();
            });
        });

        $('#a-fechar-form-criar-post').click(function(e){
            e.preventDefault();

            $(formCriarContainer).slideUp();
        });
    }


    var formPost = document.getElementById('form-criar-post');
    formPost = ( formPost == null ) ? document.getElementById('form-alterar-post') : formPost;

    if ( formPost != null ) {
        $('#btn-form-post').click(function(e){
            e.preventDefault();

            var url = WeLearn.url.siteURL('/forum/post/salvar');

            WeLearn.validarForm(
                formPost,
                url,
                function(res) {
                    var hdnAcao = $( document.getElementsByName('acao') ).first().val(),
                        $divListaPosts;

                    if ( hdnAcao == 'criar' ) {

                        var divListaPosts = document.getElementById('forum-lista-posts');

                        if ( divListaPosts == null ) {
                            $divListaPosts = $('<div id="forum-lista-posts"></div>');

                            var $divListaPostsVazio = $('#forum-lista-posts-vazio');

                            $divListaPostsVazio.before( $divListaPosts );

                            $divListaPostsVazio.remove();
                        } else {
                            $divListaPosts = $('#forum-lista-posts');
                        }

                        var $artNovoPost = $(res.htmlNovoPost);

                        $artNovoPost.hide();

                        $divListaPosts.append($artNovoPost);

                        $(formCriarContainer).slideUp();

                        setTimeout(function(){
                            formPost.reset();
                            $artNovoPost.fadeIn(600);
                        }, 600);

                    } else if ( hdnAcao == 'alterar' ) {
                        window.location = WeLearn.url.siteURL('/curso/forum/post/listar/' + res.idForum)
                    }
                }
            )
        });
    }

    $('.a-remover-post').live('click', function(e){
        e.preventDefault();

        var $divDialogo = $('<div id="dialogo-confirmacao-remover-post">' +
                            '<p>Tem certeza que deseja remover este Post?<br/>' +
                            'Esta ação <strong>NÃO</strong> poderá ser desfeita.</p></div>'),
            $this = $( this );

        $divDialogo.dialog({
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
                                     .next()
                                     .andSelf()
                                     .fadeOut(600, function(){
                                         $( this ).remove();
                                     });

                                $dialogThis.dialog('close');
                                WeLearn.notificar(res.notificacao);

                            } else {
                                WeLearn.notificar({
                                    nivel: 'erro',
                                    msg: res.errors[0].error_msg,
                                    tempo: 10000
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

    $('#paginacao-lista-post a').click(function(e) {
        e.preventDefault();

        var $this = $(this),
            idProximo = $this.data('proximo'),
            idForum = $this.data('id-forum'),
            url = WeLearn.url.siteURL('/forum/post/proxima_pagina/' + idForum + '/' + idProximo);

        $.get(
            url,
            {},
            function(res) {
                if (res.success) {

                    $('#forum-lista-posts').prepend(res.htmlListaPosts);

                    if (res.dadosPaginacao.proxima_pagina) {
                        $this.data('proximo', res.dadosPaginacao.inicio_proxima_pagina);
                    } else {
                        $this.parent().html('<h4>Não há mais posts a serem exibidos.</h4>');
                    }

                } else {
                    WeLearn.notificar({
                        nivel: 'erro',
                        msg: res.errors[0].error_msg,
                        tempo: 10000
                    });
                }
            },
            'json'
        )
    });
});