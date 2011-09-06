/**
 * Created by JetBrains PhpStorm.
 * User: Allan
 * Date: 13/08/11
 * Time: 14:18
 * To change this template use File | Settings | File Templates.
 */
$(document).ready(function(){
    var $btnCadastrar = $('button[name=cadastrar]'),
        $sltArea = $('#slt-area');

    $sltArea.change(function(e){
        var $lblSegmento = $('label[for=txt-segmento]').parent(),
            $sltSegmento = $('#slt-segmento'),
            $ddSegmento = $sltSegmento.parent();
            
        $sltSegmento.html('');
        $lblSegmento.addClass('hidden');
        $ddSegmento.addClass('hidden');
        
        var $error = $(this).next('p.error');
        if ($error) {
            $error.remove();
        }
        
        if ( $(this).val() == "" ) {
            return false;
        }

        $.get(
            'http://welearn.com/segmento/recuperar_lista/' + $(this).val(),
            null,
            function(res){
                if (res.success) {
                    var html = WeLearn.segmento.gerarOpcoesHTML(res.segmentos);
                    
                    $sltSegmento.html(html);
                    $lblSegmento.removeClass('hidden');
                    $ddSegmento.removeClass('hidden');
                } else {                    
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

    $btnCadastrar.click(function(e){
        e.preventDefault();

        var form = document.forms[1];

        WeLearn.validarForm(
            form,
            'http://welearn.com/usuario/validar_cadastro',
            function(result) {
                window.location = 'http://welearn.com/quickstart';
            }
        );
    });
});