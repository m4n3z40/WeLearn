/**
 * Created with JetBrains PhpStorm.
 * User: gevigier
 * Date: 24/05/12
 * Time: 19:32
 * To change this template use File | Settings | File Templates.
 */
(function(){

    var idArea;
    var formArea = document.getElementById('form-criar-area');
    $('#btn-form-area').click(function(e){
        e.preventDefault();

        var url = $(formArea).attr('action');

        if (formArea != null) {
            WeLearn.validarForm(formArea, url, function(res) {
                window.location = WeLearn.url.siteURL('administracao/area/listar/');
            });
        }
    });

    $('#area-lista-segmento > li > a').click(function(e){
        $('#form-criar-segmento').val($(this).attr('data-id'));
        e.preventDefault();
        idArea = $(this).attr('data-id');
        $('#form-criar-segmento').hide();
        var $this = $(this),
            url = WeLearn.url.siteURL($this.attr('href')),
            $parent = $this.parent(),
            $divSegmentos = $parent.children('div');

        if ( $divSegmentos.length > 0 ) {

            $divSegmentos.slideToggle();
            return;

         }

        var htmlListaSegmentos = '<div><h3><a href="#" id="exibir-form-segmento">Adicionar Novo Segmento</a></h3><ul>';
        $.get(
            url,
            {},
            function(res) {
                var formSegmento = document.getElementById('form-criar-segmento');
                if ( res.success ) {



                        for ( var i = 0; i < res.segmentos.length; i++ ) {

                            htmlListaSegmentos += '<li>' + res.segmentos[i].descricao + '</li>';

                        }


                    htmlListaSegmentos += '</ul></div>';

                    var $htmlSegmentos = $(htmlListaSegmentos).hide();

                    $parent.append( $htmlSegmentos );

                    $htmlSegmentos.slideDown();

                }else{



                    htmlListaSegmentos += '</ul></div>';

                    var $htmlSegmentos = $(htmlListaSegmentos).hide();

                    $parent.append( $htmlSegmentos );

                    $htmlSegmentos.slideDown();

                }
            }
        );
      });

    $('#exibir-form-segmento').live('click',function(e){
        $('#descricao-segmento').val('');
        $('#form-criar-segmento').hide();
        e.preventDefault();
        $(this).parent().append($('#form-criar-segmento'));
        $('#form-criar-segmento').show();
        $('#descricao-segmento').focus();
    });
    $('#btn-form-segmento').live('click',
        function(e){
            e.preventDefault();
            $('#idarea').val(idArea);
            var form = document.getElementById('form-criar-segmento'),
                url = $(form).attr('action');
                WeLearn.validarForm(form,url,function(res){
                window.location.reload();
            });
        });

})();
