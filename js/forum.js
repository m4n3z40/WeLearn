/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 17/11/11
 * Time: 13:40
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(e){
    var navPaginacaoCategorias = document.getElementById('paginacao-forum-categorias');
    if (navPaginacaoCategorias != null) {
        var $aProxPagina = $(navPaginacaoCategorias).find('a');

        $aProxPagina.click(function(e) {
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
                    if ( res.success ) {
                        alert('Sucesso!');
                    }
                });
            }
        });
    }
});