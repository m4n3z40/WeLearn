/**
 * Created by JetBrains PhpStorm.
 * User: administrador
 * Date: 19/10/11
 * Time: 02:15
 * To change this template use File | Settings | File Templates.
 */

$(document).ready(function(){
    var btnFormCategoriaForum = document.getElementById('btn-form-categoria-forum');
    if (btnFormCategoriaForum != null) {
        $(btnFormCategoriaForum).click(function(e){
            e.preventDefault();

            var formCategoria = document.getElementById('form-criar-categoria-forum'),
                url = WeLearn.url.siteURL('forum/categoria/salvar');
            if (formCategoria != null) {
                WeLearn.validarForm(formCategoria, url, function(res) {
                   if ( res.success ) {
                       window.location = WeLearn.url.siteURL('curso/' + res.idCurso + '/forum/categoria/listar');
                   }
                });
            }
        });
    }

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
                            $this.parent().html('Não há mais categorias a serem exibidas no momento.');
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
});