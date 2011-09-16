/**
 * Created by JetBrains PhpStorm.
 * User: Allan Baptista (allan.marques@ymail.com)
 * Date: 08/09/11
 * Time: 13:13
 */

$(document).ready(function(){
    $('#slt-area').change(function(e){
        var vazio = '<option value="0">Selecione uma área de segmento</option>';

        $(this).next('p.error').remove();

        if ($(this).val() == '0') {
            $('#slt-segmento').html(vazio);
            return;
        }

        $.get(
            'http://welearn.com/segmento/recuperar_lista/' + $(this).val(),
            null,
            function(res){
                if (res.success) {
                    var html = WeLearn.segmento.gerarOpcoesHTML(res.segmentos);

                    $('#slt-segmento').html(html);
                } else {
                    $('#slt-segmento').html(vazio);

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
