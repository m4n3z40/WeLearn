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
        var $lblSegmento = $('label[for=slt-segmento]').parent(),
            $sltSegmento = $('#slt-segmento'),
            $ddSegmento = $sltSegmento.parent();
            
        $sltSegmento.html('');
        $lblSegmento.hide();
        $ddSegmento.hide();
        
        var $error = $(this).next('p.error');
        if ($error) {
            $error.remove();
        }
        
        if ( $(this).val() == '0' || $(this).val() == '' ) {
            return false;
        }

        $.get(
            WeLearn.url.siteURL('segmento/recuperar_lista/' + $(this).val()),
            null,
            function(res){
                if (res.success) {
                    var html = WeLearn.segmento.gerarOpcoesHTML(res.segmentos);
                    
                    $sltSegmento.html(html);
                    $lblSegmento.show('fast');
                    $ddSegmento.show('fast');
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

        var $sltsSegmentos = $('#slt-area option:selected,#slt-segmento option:selected');

        $sltsSegmentos.each(function(){
            if( $(this).val() == '0' ) {
                $(this).val('');
            }
        });

        var form = document.forms[1];

        WeLearn.validarForm(
            form,
            WeLearn.url.siteURL('usuario/validar_cadastro'),
            function(result) {
                window.location = WeLearn.url.siteURL('quickstart');
            }
        );
    });
});