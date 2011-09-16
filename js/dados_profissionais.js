/**
 * Created by JetBrains PhpStorm.
 * User: Allan Baptista (allan.marques@ymail.com)
 * Date: 06/09/11
 * Time: 14:18
 */

$(document).ready(function(){
    var $sltArea = $('#slt-area');

    $sltArea.change(function(e){
        var $sltSegmento = $('#slt-segmento'),
            vazio = '<option value="0">Selecione uma área de segmento</option>';

        $(this).next('p.error').remove();

        if($(this).val() == '0') {
            $sltSegmento.val(vazio);
            return;
        }

        $.get(
            'http://welearn.com/segmento/recuperar_lista/' + $(this).val(),
            null,
            function(res){
                if (res.success) {
                    var html = WeLearn.segmento.gerarOpcoesHTML(res.segmentos);

                    $sltSegmento.html(html);
                } else {
                    $sltSegmento.html(vazio);

                    var errors = res.errors;

                    for (var i = 0; i < errors.length; i++) {
                        $('select[name='+errors[i].field_name+']').after(
                            '<p class="error">'+errors[i].error_msg+'</p>'
                        );
                    }
                }
            }
        );
    });
});
