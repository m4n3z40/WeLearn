/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 20/04/12
 * Time: 20:00
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(
    function(){
        $('#proximaPaginaUsuarios').click(
            function(e){
                e.preventDefault();
                var idProxPagina=$(this).parent().children('#id-prox-pagina').val();
                var texto=$(this).parent().children('#texto').val();
                var url = WeLearn.url.siteURL('usuario/busca/proxima_pagina/' +texto+'/'+idProxPagina );

                $.get(
                    url,
                    (WeLearn.url.queryString != '') ? WeLearn.url.queryString : null,
                    function(res) {
                        if (res.success) {
                            $('#listaUsuarios').append(res.htmlListaUsuarios);

                            if(res.paginacao.proxima_pagina) {
                                $('#id-prox-pagina').val(res.inicioProxPagina);
                            } else {
                                $('#id-prox-pagina').parent().html('<h4>Não Existem mais resultados.</h4>');
                                $('#id-prox-pagina').remove();
                            }
                        }else {
                            WeLearn.notificar({
                                msg: res.errors[0].error_msg,
                                nivel: 'error',
                                tempo: 10000
                            });
                        }
                    },'json'
                )

            });

        $('enviar').click(
            function(e)
            {
                alert('teste');
            }
        );

    });
